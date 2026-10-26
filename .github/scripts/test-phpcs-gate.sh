#!/usr/bin/env bash
set -euo pipefail

BASE_SHA="$(git rev-parse HEAD)"
JSON_FILE="$(mktemp)"
FAILURES=0
PYTHON="${PYTHON:-python3}"
PHPCS_CHANGED="${PHPCS_CHANGED:-vendor/bin/phpcs-changed}"

git config user.name "phpcs-gate-test"
git config user.email "phpcs-gate-test@example.invalid"

reset_tree() {
	git reset --hard "$BASE_SHA" >/dev/null
}

check_json() {
	php -r '
		$data = json_decode( file_get_contents( $argv[1] ), true );
		if ( ! is_array( $data ) ) {
			fwrite( STDERR, "Invalid PHPCS JSON output\n" );
			exit( 2 );
		}
		$errors = (int) ( $data["totals"]["errors"] ?? 0 );
		if ( $errors > 0 ) {
			fwrite( STDERR, "New PHPCS errors found: {$errors}\n" );
			exit( 1 );
		}
		$warnings = (int) ( $data["totals"]["warnings"] ?? 0 );
		if ( $warnings > 0 ) {
			echo "New PHPCS warnings found: {$warnings} (non-blocking)\n";
		}
	' "$1"
}

run_gate() {
	local base="$1"
	shift
	local files=("$@")
	local tool_exit=0

	set +e
	"$PHPCS_CHANGED" \
		--git \
		--git-base "$base" \
		--no-batch \
		--standard=phpcs.xml \
		--report=json \
		"${files[@]}" > "$JSON_FILE"
	tool_exit=$?

	cat "$JSON_FILE"

	if [[ $tool_exit -gt 1 ]]; then
		echo "phpcs-changed failed unexpectedly with exit code $tool_exit"
		return "$tool_exit"
	fi

	check_json "$JSON_FILE"
	local check_exit=$?
	return "$check_exit"
}

record_result() {
	local label="$1"
	local expected="$2"
	local actual="$3"

	if [[ "$actual" -eq "$expected" ]]; then
		echo "PASS: $label (exit $actual)"
	else
		echo "FAIL: $label (expected exit $expected, got $actual)"
		FAILURES=$((FAILURES + 1))
	fi
}

make_clean_change() {
	"$PYTHON" - <<'PY'
from pathlib import Path

path = Path("wp-content/themes/hds/inc/service-functions.php")
text = path.read_text()
old = "esc_html_e( 'Lees meer', 'hds' )"
new = "esc_html_e( 'Lees meer!', 'hds' )"
if old not in text:
    raise SystemExit("clean-change target not found")
path.write_text(text.replace(old, new, 1))
PY
	git add wp-content/themes/hds/inc/service-functions.php
	git commit --no-verify -m "test: clean change" >/dev/null
}

make_new_error() {
	"$PYTHON" - <<'PY'
from pathlib import Path

path = Path("wp-content/themes/hds/inc/service-functions.php")
text = path.read_text()
old = "if ( '' === $excerpt ) {"
new = "if ( $excerpt === '' ) {"
if old not in text:
    raise SystemExit("new-error target not found")
path.write_text(text.replace(old, new, 1))
PY
	git add wp-content/themes/hds/inc/service-functions.php
	git commit --no-verify -m "test: new error" >/dev/null
}

make_new_warning() {
	"$PYTHON" - <<'PY'
from pathlib import Path

path = Path("wp-content/themes/hds/inc/services.php")
text = path.read_text()
marker = "'scholen-en-kinderopvang-reiniging' => ["
old = "'hero_image'      => 0,"
new = "'hero_image' => 0,"
index = text.index(marker)
before, after = text[:index], text[index:]
if old not in after:
    raise SystemExit("new-warning target not found")
path.write_text(before + after.replace(old, new, 1))
PY
	git add wp-content/themes/hds/inc/services.php
	git commit --no-verify -m "test: new warning" >/dev/null
}

reset_tree
make_clean_change
set +e
run_gate "$BASE_SHA" wp-content/themes/hds/inc/service-functions.php
gate_exit=$?
set -e
record_result "unchanged pre-existing PHPCS errors do not fail" 0 "$gate_exit"

reset_tree
make_new_error
set +e
run_gate "$BASE_SHA" wp-content/themes/hds/inc/service-functions.php
gate_exit=$?
set -e
record_result "new PHPCS error fails" 1 "$gate_exit"

reset_tree
make_new_warning
set +e
warning_output="$(run_gate "$BASE_SHA" wp-content/themes/hds/inc/services.php 2>&1)"
gate_exit=$?
set -e
echo "$warning_output"
if [[ $gate_exit -eq 0 && "$warning_output" == *"New PHPCS warnings found: 1 (non-blocking)"* ]]; then
	echo "PASS: new warning is reported but does not fail"
else
	echo "FAIL: new warning handling (exit $gate_exit)"
	FAILURES=$((FAILURES + 1))
fi

bad_json_file="$(mktemp)"
printf 'not-json' > "$bad_json_file"
set +e
check_json "$bad_json_file" >/dev/null 2>&1
gate_exit=$?
set -e
record_result "invalid JSON fails" 2 "$gate_exit"

fake_tool="$(mktemp)"
printf '#!/usr/bin/env bash\nexit 2\n' > "$fake_tool"
chmod +x "$fake_tool"
set +e
PHPCS_CHANGED="$fake_tool" run_gate "$BASE_SHA" wp-content/themes/hds/inc/service-functions.php >/dev/null 2>&1
gate_exit=$?
set -e
if [[ $gate_exit -gt 1 ]]; then
	echo "PASS: unexpected PHPCS execution error fails (exit $gate_exit)"
else
	echo "FAIL: unexpected PHPCS execution error (exit $gate_exit)"
	FAILURES=$((FAILURES + 1))
fi

reset_tree
if [[ -n "$(git status --porcelain --untracked-files=no)" ]]; then
	echo "FAIL: workspace not clean after tests"
	git status --short
	FAILURES=$((FAILURES + 1))
else
	echo "PASS: workspace clean after tests"
fi

if [[ $FAILURES -ne 0 ]]; then
	echo "FAILED: $FAILURES test(s)"
	exit 1
fi

echo "All PHPCS gate tests passed."
