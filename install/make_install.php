<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// -------------------------------------------------------------------------
// 1. Load private.ini
// -------------------------------------------------------------------------
$iniPath = __DIR__ . "/../../private.ini";
if (!file_exists($iniPath)) {
    die("<h1 style='color:red;'>private.ini is missing: $iniPath</h1>");
}

$config = parse_ini_file($iniPath, true);

$dbHost   = $config['database_connection']['dbhost'];
$dbUser   = $config['database_connection']['dbuser'];
$dbPass   = $config['database_connection']['dbpass'];
$dbName   = $config['database_connection']['dbname'];
$dbPrefix = $config['database_connection']['dbprefix'];

// -------------------------------------------------------------------------
// 2. Security — installer is password protected (DB password)
// -------------------------------------------------------------------------
session_start();
$expectedPassword = $dbPass;

if (!isset($_SESSION['authenticated'])) {

    if (isset($_POST['login_password'])) {
        if ($_POST['login_password'] === $expectedPassword) {
            $_SESSION['authenticated'] = true;
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } else {
            $error = "Incorrect password.";
        }
    }

    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>WCIO Installer Login</title>
        <style>
            body {
                background: #f5f6fa;
                font-family: "Segoe UI", Roboto, Arial, sans-serif;
                display: flex;
                align-items: center;
                justify-content: center;
                height: 100vh;
                margin: 0;
            }
            .login-box {
                background: #fff;
                padding: 40px 50px;
                border-radius: 12px;
                box-shadow: 0 8px 25px rgba(0,0,0,0.08);
                width: 380px;
                text-align: center;
            }
            h1 {
                font-size: 24px;
                font-weight: 600;
                margin-bottom: 20px;
                color: #333;
            }
            .error {
                background: #ffdddd;
                border: 1px solid #ff8a8a;
                padding: 10px;
                border-radius: 6px;
                color: #b30000;
                margin-bottom: 15px;
            }
            label { font-size: 14px; color: #444; display: block; text-align: left; margin-bottom: 8px; }
            input[type=password] {
                width: 100%; padding: 12px; border-radius: 6px;
                border: 1px solid #ccc; margin-bottom: 20px; font-size: 15px;
            }
            button {
                width: 100%; background: #0069d9; color: white;
                padding: 12px; font-size: 16px; border: none; border-radius: 6px;
                cursor: pointer; transition: 0.2s;
            }
            button:hover { background: #0053b3; }
            .footer { margin-top: 20px; font-size: 12px; color: #666; }
        </style>
    </head>

    <body>
        <div class="login-box">

            <h1>WCIO Installer Login</h1>

            <?php if (!empty($error)): ?>
                <div class="error"><?= htmlspecialchars($error, ENT_QUOTES) ?></div>
            <?php endif; ?>

            <form method="post">
                <label for="pw">Database Password</label>
                <input type="password" id="pw" name="login_password" autocomplete="off" required>

                <button type="submit">Continue</button>
            </form>

            <div class="footer">Protected Installer • WCIO Shop</div>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// -------------------------------------------------------------------------
// 3. DB connection
// -------------------------------------------------------------------------
$mysqli = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
if ($mysqli->connect_errno) {
    die("<h1 style='color:red;'>Database connection failed: " . htmlspecialchars($mysqli->connect_error, ENT_QUOTES) . "</h1>");
}
$mysqli->set_charset('utf8mb4');

// -------------------------------------------------------------------------
// 4. Helper functions
// -------------------------------------------------------------------------

function h($str) {
    return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8');
}

function isValidIdentifier(string $name): bool {
    // Kun bogstaver, tal og underscore
    return (bool)preg_match('/^[A-Za-z0-9_]+$/', $name);
}
/**
 * Generate a demo value for a settings row (wcioshop_settings style).
 * Uses columnName + some heuristics, like a tiny "AI".
 */
function generateSettingsDemoValue(array $row): string {
    $name  = strtolower($row['columnName'] ?? '');
    $value = (string)($row['columnValue'] ?? '');

    // Emails
    if (str_contains($name, 'saleemail') || str_contains($name, 'salenotification')) {
        return 'sales@example.com';
    }
    if (str_contains($name, 'adminnotification')) {
        return 'admin@example.com';
    }
    if (str_contains($name, 'email')) {
        return 'info@example.com';
    }

    // URL / Domain
    if (str_contains($name, 'url') || str_contains($name, 'domain')) {
        return 'example.com';
    }

    // Store name / SEO name / slogan
    if (str_contains($name, 'storename') || str_contains($name, 'shopname')) {
        return 'Demo Shop';
    }
    if (str_contains($name, 'seoshortname')) {
        return 'Demo Shop';
    }
    if (str_contains($name, 'slogan')) {
        return 'Beautiful demo products for everyone';
    }

    // Address
    if (str_contains($name, 'address')) {
        return 'Demo Street 123, 1000 Demo City';
    }

    // Currency
    if (str_contains($name, 'currencies') || str_contains($name, 'currency')) {
        if (str_contains($name, 'format')) {
            // Example format: 2:',':'.'
            return "2:',':'.'";
        }
        if (str_contains($name, 'shown')) {
            return '€';
        }
        return 'EUR';
    }

    // VAT
    if (str_contains($name, 'vat')) {
        return '25';
    }

    // Language
    if (str_contains($name, 'languageadmin')) {
        return 'EN';
    }
    if (str_contains($name, 'language')) {
        return 'EN';
    }

    // Maintenance mode
    if (str_contains($name, 'maintenancemode') || str_contains($name, 'maintenance')) {
        if (str_contains($name, 'message')) {
            return '<p>This shop is currently in demo maintenance mode.</p>';
        }
        return '0';
    }

    // Invoice / counters / numeric fields
    if (str_contains($name, 'lastinvoice')) {
        return '0';
    }

    // Header / footer / widget-like HTML fields
    if (str_contains($name, 'headercode')) {
        return "<!-- Demo header code placeholder -->";
    }
    if (str_contains($name, 'footercode')) {
        return "<!-- Demo footer code placeholder -->";
    }
    if (str_contains($name, 'footerwidget')) {
        return "<h3>Demo Footer Widget</h3><p>Replace this with your footer content.</p>";
    }

    // Stock / catalog mode
    if (str_contains($name, 'usestocklevel')) {
        return '1';
    }
    if (str_contains($name, 'catalogmode')) {
        return '0';
    }

    // Mail service
    if (str_contains($name, 'mailserivce') || str_contains($name, 'mailservice')) {
        if (str_contains($name, 'smtpserver')) {
            return 'smtp.example.com';
        }
        if (str_contains($name, 'smtpport')) {
            return '587';
        }
        if (str_contains($name, 'smtpusername')) {
            return 'user@example.com';
        }
        if (str_contains($name, 'smtppassword')) {
            return 'demo-password';
        }
        return 'phpmail';
    }

    // Phone
    if (str_contains($name, 'phone') || str_contains($name, 'tel')) {
        return '+4512345678';
    }

    // Default fallback
    if ($value === '') {
        return 'demo';
    }

    // If it’s a long HTML/JS string, just hint that it’s demo
    if (strlen($value) > 200) {
        return 'Demo content';
    }

    // Slight variation: reuse original but mark as demo-ish
    return $value;
}

function getTableColumns(mysqli $mysqli, string $table): array {
    $cols = [];
    if (!isValidIdentifier($table)) {
        return $cols;
    }
    if ($res = $mysqli->query("SHOW COLUMNS FROM `$table`")) {
        while ($row = $res->fetch_assoc()) {
            $cols[] = $row['Field'];
        }
    }
    return $cols;
}

/**
 * Generate a demo admin user row for a given table (no password).
 * Columns are discovered from SHOW COLUMNS.
 */
function generateAdminUserDemoRow(mysqli $mysqli, string $table): ?array {
   
      if (!isValidIdentifier($table)) {
        return null;
    }

    $columnsRes = $mysqli->query("SHOW COLUMNS FROM `$table`");
    if (!$columnsRes) return null;


    $row = [];
    while ($col = $columnsRes->fetch_assoc()) {
        $field = $col['Field'];
        $lname = strtolower($field);

        if ($lname === 'id' || str_contains($lname, 'id')) {
            $row[$field] = 1;
        } elseif (str_contains($lname, 'user') && str_contains($lname, 'name')) {
            $row[$field] = 'admin';
        } elseif ($lname === 'username') {
            $row[$field] = 'admin';
        } elseif (str_contains($lname, 'email')) {
            $row[$field] = 'admin@example.com';
        } elseif (str_contains($lname, 'role') || str_contains($lname, 'type')) {
            $row[$field] = 'admin';
        } elseif (str_contains($lname, 'pass') || str_contains($lname, 'pwd')) {
            // No password – will be set during install to DB password
            $row[$field] = '';
        } else {
            $row[$field] = 'demo';
        }
    }

    if (empty($row)) return null;
    return $row;
}

// -------------------------------------------------------------------------
// 5. Fetch all prefixed tables
// -------------------------------------------------------------------------
$tables = [];
$res = $mysqli->query("SHOW TABLES LIKE '{$dbPrefix}%'");
while ($row = $res->fetch_array()) {
    $tables[] = $row[0];
}

// -------------------------------------------------------------------------
// 6. Determine step
// -------------------------------------------------------------------------
$step = $_POST['step'] ?? 'choose_tables';

// ======================================================================
// STEP 1: Choose tables + datatype
// ======================================================================
if ($step === 'choose_tables') {

    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>WCIO Install Builder</title>
        <style>
            body {
                font-family: "Segoe UI", Roboto, Arial, sans-serif;
                background: #eef1f5;
                padding: 40px;
            }
            .container {
                background: #fff;
                padding: 30px 40px;
                max-width: 900px;
                margin: auto;
                border-radius: 12px;
                box-shadow: 0 8px 25px rgba(0,0,0,0.08);
            }
            h1 { font-size: 26px; margin-bottom: 10px; color: #333; }
            p.subtitle { color: #666; margin-top: 0; margin-bottom: 20px; }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 25px;
            }
            th, td { padding: 10px; border-bottom: 1px solid #ddd; font-size: 14px; }
            th { text-align: left; background: #f7f8fa; }
            select, input[type=checkbox] { font-size: 14px; }
            button {
                background: #28a745;
                color: white;
                padding: 12px 20px;
                border: none;
                border-radius: 6px;
                font-size: 16px;
                cursor: pointer;
                transition: 0.2s;
            }
            button:hover { background: #218838; }
        </style>
    </head>

    <body>
        <div class="container">
            <h1>WCIO Install Builder</h1>
            <p class="subtitle">
                Step 1: Choose which tables to include and how data should be handled.
                Default is <strong>Empty (schema only)</strong>.
            </p>

            <form method="post">
                <input type="hidden" name="step" value="select_rows">

                <table>
                    <tr>
                        <th style="width: 40%;">Table</th>
                        <th>Data mode</th>
                    </tr>
                    <?php foreach ($tables as $tbl): ?>
                        <tr>
                            <td>
                                <label>
                                    <input type="checkbox" name="tables[]" value="<?= h($tbl) ?>" checked>
                                    <?= h($tbl) ?>
                                </label>
                            </td>
                            <td>
                                <select name="datatype[<?= h($tbl) ?>]">
                                    <option value="empty" selected>Empty (schema only)</option>
                                    <option value="real">Use real data (select rows)</option>
                                    <option value="demo">Use demo data (AI generated, editable & selectable rows)</option>
                                </select>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>

                <button type="submit">Next: Select rows</button>
            </form>
        </div>
    </body>
    </html>
    <?php

    exit;
}

// ======================================================================
// STEP 2: Row selection (for real/demo)
// ======================================================================
if ($step === 'select_rows') {

// Whitelist tabelnavne mod faktiske tabeller fra DB
$selectedTables = array_values(array_unique($selectedTables));
$validSelected = [];

foreach ($selectedTables as $t) {
    if (!in_array($t, $tables, true)) {
        continue; // ukendt tabel – ignorer
    }
    if (!isValidIdentifier($t)) {
        continue; // tegn vi ikke vil acceptere
    }
    $validSelected[] = $t;
}

$selectedTables = $validSelected;

if (empty($selectedTables)) {
    die("<h2>No valid tables selected.</h2>");
}

    $selectedTables = $_POST['tables'] ?? [];
    $datatype       = $_POST['datatype'] ?? [];

    if (empty($selectedTables)) {
        die("<h2>No tables selected.</h2>");
    }

    // Determine if any table actually uses real/demo
    $needRowSelection = false;
    foreach ($selectedTables as $t) {
        if (!empty($datatype[$t]) && $datatype[$t] !== 'empty') {
            $needRowSelection = true;
            break;
        }
    }

    // If no tables need data → skip to SQL generation directly
    if (!$needRowSelection) {
        $_POST['step'] = 'build_sql';
        $_POST['rowdata'] = [];
        $_POST['rows'] = [];
        // fall through to build_sql logic below
    } else {

        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>WCIO Install Builder – Select Rows</title>
            <style>
                body {
                    font-family: "Segoe UI", Roboto, Arial, sans-serif;
                    background: #eef1f5;
                    padding: 40px;
                }
                .container {
                    background: #fff;
                    padding: 30px 40px;
                    max-width: 1000px;
                    margin: auto;
                    border-radius: 12px;
                    box-shadow: 0 8px 25px rgba(0,0,0,0.08);
                }
                h1 { font-size: 26px; margin-bottom: 10px; color: #333; }
                p.subtitle { color: #666; margin-top: 0; margin-bottom: 20px; }
                h2.table-title {
                    margin-top: 30px;
                    font-size: 18px;
                    color: #333;
                    border-bottom: 1px solid #ddd;
                    padding-bottom: 5px;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-bottom: 15px;
                }
                th, td {
                    padding: 8px;
                    border-bottom: 1px solid #eee;
                    font-size: 13px;
                    vertical-align: top;
                }
                th {
                    background: #f7f8fa;
                    text-align: left;
                }
                .small {
                    font-size: 12px;
                    color: #555;
                    max-width: 400px;
                    word-break: break-word;
                }
                .demo-value {
                    font-size: 12px;
                    color: #2c7a2c;
                    max-width: 400px;
                    word-break: break-word;
                }
                .demoinput {
                    width: 100%;
                    padding: 5px;
                    font-size: 12px;
                    font-family: inherit;
                    box-sizing: border-box;
                }
                button {
                    background: #28a745;
                    color: white;
                    padding: 12px 20px;
                    border: none;
                    border-radius: 6px;
                    font-size: 16px;
                    cursor: pointer;
                    transition: 0.2s;
                    margin-top: 15px;
                }
                button:hover { background: #218838; }
                .note {
                    font-size: 12px;
                    color: #666;
                    margin-bottom: 5px;
                }
            </style>
        </head>

        <body>
        <div class="container">
            <h1>WCIO Install Builder</h1>
            <p class="subtitle">
                Step 2: Select which rows should be included. All rows are checked by default.
                For demo mode, you can edit the demo values before export.
            </p>

            <form method="post">
                <input type="hidden" name="step" value="build_sql">

                <?php
                // persist table + datatype selection
                foreach ($selectedTables as $tbl) {
                    echo '<input type="hidden" name="tables[]" value="'.h($tbl).'">'.PHP_EOL;
                    $dt = $datatype[$tbl] ?? 'empty';
                    echo '<input type="hidden" name="datatype['.h($tbl).']" value="'.h($dt).'">'.PHP_EOL;
                }

                $rowdata = [];

                foreach ($selectedTables as $tbl) {
                    $mode = $datatype[$tbl] ?? 'empty';
                    if ($mode === 'empty') {
                        continue;
                    }

                    echo '<h2 class="table-title">'.h($tbl).' (mode: '.h($mode).')</h2>';

                    // SETTINGS TABLES (name contains 'settings')
                    if (stripos($tbl, 'settings') !== false) {

                        $sql = "SELECT * FROM `$tbl` ORDER BY id ASC";
                        $res = $mysqli->query($sql);

                        if (!$res || $res->num_rows === 0) {
                            echo "<p class='note'>No rows found in settings table.</p>";
                            continue;
                        }

                        echo "<p class='note'>For demo mode, the <strong>Demo value</strong> field is editable and will be exported.</p>";
                        echo "<table>";
                        echo "<tr>
                                <th>Use</th>
                                <th>Column name</th>
                                <th>Current value</th>";
                        if ($mode === 'demo') {
                            echo "<th>Demo value (editable)</th>";
                        }
                        echo "</tr>";

                        $i = 0;
                        while ($row = $res->fetch_assoc()) {
                            $currentVal = $row['columnValue'] ?? '';
                            $demoRow   = $row;

                            if ($mode === 'demo') {
                                $demoRow['columnValue'] = generateSettingsDemoValue($row);
                                $demoVal = $demoRow['columnValue'];
                            } else {
                                $demoVal = '';
                            }

                            // store row used for export (either real or demo)
                            $rowdata[$tbl][$i] = $mode === 'demo' ? $demoRow : $row;

                            echo "<tr>";
                            echo "<td><input type='checkbox' name='rows[".h($tbl)."][".$i."]' value='1' checked></td>";
                            echo "<td class='small'>".h($row['columnName'])."</td>";
                            echo "<td class='small'>".h(mb_strimwidth($currentVal, 0, 120, '…'))."</td>";
                            if ($mode === 'demo') {
                                // Editable demo value: input or textarea depending on length
                                if (strlen($demoVal) > 120) {
                                    echo "<td><textarea class='demoinput' name='demoedit[".h($tbl)."][".$i."]' rows='3'>".h($demoVal)."</textarea></td>";
                                } else {
                                    echo "<td><input type='text' class='demoinput' name='demoedit[".h($tbl)."][".$i."]' value='".h($demoVal)."'></td>";
                                }
                            }
                            echo "</tr>";

                            $i++;
                        }

                        echo "</table>";

                    }
                    // USERS TABLES (name contains 'user') – demo admin user
                    elseif (stripos($tbl, 'user') !== false && $mode === 'demo') {

                        $demoUser = generateAdminUserDemoRow($mysqli, $tbl);
                        if (!$demoUser) {
                            echo "<p class='note'>Could not determine columns for users table, skipping demo row.</p>";
                            continue;
                        }

                        echo "<p class='note'>A single demo admin user will be created (no password, set during install).</p>";
                        echo "<table>";
                        echo "<tr><th>Use</th><th>Field</th><th>Demo value</th></tr>";

                        // store as index 0
                        $rowdata[$tbl][0] = $demoUser;

                        echo "<tr>";
                        echo "<td><input type='checkbox' name='rows[".h($tbl)."][0]' value='1' checked></td>";
                        echo "<td class='small'>admin user</td>";
                        echo "<td class='demo-value'>username: admin, email: admin@example.com, role: admin</td>";
                        echo "</tr>";
                        echo "</table>";
                    }
                    // REAL DATA FOR NON-SETTINGS / NON-USERS
                    elseif ($mode === 'real') {

                        $sql = "SELECT * FROM `$tbl`";
                        $res = $mysqli->query($sql);

                        if (!$res || $res->num_rows === 0) {
                            echo "<p class='note'>No rows found.</p>";
                            continue;
                        }

                        echo "<p class='note'>Real data will be exported as-is for checked rows.</p>";
                        echo "<table>";
                        echo "<tr><th>Use</th><th>Row (JSON preview)</th></tr>";

                        $i = 0;
                        while ($row = $res->fetch_assoc()) {
                            $rowdata[$tbl][$i] = $row;
                            $preview = json_encode($row, JSON_UNESCAPED_UNICODE);
                            echo "<tr>";
                            echo "<td><input type='checkbox' name='rows[".h($tbl)."][".$i."]' value='1' checked></td>";
                            echo "<td class='small'>".h(mb_strimwidth($preview, 0, 140, '…'))."</td>";
                            echo "</tr>";
                            $i++;
                        }

                        echo "</table>";
                    }
                    // DEMO FOR OTHER TABLES (generic, not configured)
                    else {
                        echo "<p class='note'>Demo mode is not configured for this table. No data will be exported.</p>";
                    }
                } // end foreach tables

                // emit hidden rowdata with base64-encoded JSON
                foreach ($rowdata as $tblName => $rowsForTable) {
                    foreach ($rowsForTable as $idx => $dataRow) {
                        $encoded = base64_encode(json_encode($dataRow));
                        echo '<input type="hidden" name="rowdata['.h($tblName).']['.$idx.']" value="'.h($encoded).'">'.PHP_EOL;
                    }
                }
                ?>

                <button type="submit">Generate SQL files</button>
            </form>
        </div>
        </body>
        </html>
        <?php

        exit;
    }
}

// ======================================================================
// STEP 3: Build SQL (from selected rows + rowdata)
// ======================================================================
if ($step === 'build_sql') {

    $selectedTables = $_POST['tables'] ?? [];
    $datatype       = $_POST['datatype'] ?? [];
    $rowdata        = $_POST['rowdata'] ?? [];
    $rowsSelected   = $_POST['rows'] ?? [];
    $demoEdits      = $_POST['demoedit'] ?? [];

$selectedTables = array_values(array_unique($selectedTables));
$validSelected = [];

foreach ($selectedTables as $t) {
    if (!in_array($t, $tables, true)) {
        continue;
    }
    if (!isValidIdentifier($t)) {
        continue;
    }
    $validSelected[] = $t;
}

$selectedTables = $validSelected;

if (empty($selectedTables)) {
    die("<h2>No valid table selected.</h2>");
}

    if (empty($selectedTables)) {
        die("<h2>No tables selected.</h2>");
    }

    $installPath = __DIR__ . "/SQL/";
    if (!is_dir($installPath)) {
        mkdir($installPath, 0777, true);
    }

    $schemaFile = $installPath . "install_schema.sql";
    $dataFile   = $installPath . "install_data.sql";

    $schemaOut = "";
    $dataOut   = "";

    foreach ($selectedTables as $table) {
        $mode = $datatype[$table] ?? 'empty';

        // --- Schema (always) ---
        $res = $mysqli->query("SHOW CREATE TABLE `$table`");
        if ($res) {
            $row = $res->fetch_assoc();
            if (!empty($row['Create Table'])) {
                $schemaOut .= $row['Create Table'] . ";\n\n";
            }
        }

    // Hent gyldige kolonner for sikkerhed
    $validColumns = getTableColumns($mysqli, $table);


        // --- Data (only if any rows selected) ---
        if (empty($rowsSelected[$table])) {
            continue;
        }

  $rowsForTable = $rowdata[$table] ?? [];

    foreach ($rowsSelected[$table] as $idx => $on) {
        if (!isset($rowsForTable[$idx])) {
            continue;
        }

        $decoded = json_decode(base64_decode($rowsForTable[$idx]), true);
        if (!is_array($decoded)) {
            continue;
        }

        // Demo-edits
        if (isset($demoEdits[$table][$idx]) && isset($decoded['columnValue'])) {
            $decoded['columnValue'] = $demoEdits[$table][$idx];
        }

        // Filtrer kolonner: kun tillad dem, der findes i tabellen
        $filtered = [];
        foreach ($decoded as $col => $val) {
            if (!is_string($col)) {
                continue;
            }
            if (!in_array($col, $validColumns, true)) {
                continue; // ukendt kolonne – smid væk
            }
            $filtered[$col] = $val;
        }

        if (empty($filtered)) {
            continue;
        }

        $columns = array_keys($filtered);
        $values  = array_values($filtered);

        $colsEsc = [];
        foreach ($columns as $c) {
            if (!isValidIdentifier($c)) {
                continue 2; // hele rækken droppes
            }
            $colsEsc[] = '`' . $c . '`';
        }

        $valsEsc = array_map(function($v) use ($mysqli) {
            if ($v === null) {
                return "NULL";
            }
            return "'" . $mysqli->real_escape_string((string)$v) . "'";
        }, $values);

        $dataOut .= "INSERT INTO `$table` ("
            . implode(",", $colsEsc)
            . ") VALUES ("
            . implode(",", $valsEsc)
            . ");\n";
    }

    $dataOut .= "\n";
}

    file_put_contents($schemaFile, $schemaOut);
    file_put_contents($dataFile, $dataOut);

    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>WCIO Install Builder – Done</title>
        <style>
            body {
                background: #eef1f5;
                font-family: "Segoe UI", Roboto, Arial, sans-serif;
                padding: 40px;
            }
            .box {
                background: #fff;
                padding: 30px 40px;
                max-width: 700px;
                margin: auto;
                border-radius: 12px;
                box-shadow: 0 8px 25px rgba(0,0,0,0.08);
            }
            h1 { font-size: 28px; color: #2d862d; }
            p { font-size: 16px; }
            code {
                background: #f1f3f6;
                padding: 5px 10px;
                border-radius: 6px;
                display: block;
                margin: 10px 0;
                font-size: 13px;
            }
        </style>
    </head>
    <body>
    <div class="box">
        <h1>SQL Files Generated</h1>

        <p>The following files were created:</p>

        <code><?= h($schemaFile) ?></code>
        <code><?= h($dataFile) ?></code>

        <p>You can now commit them to GitHub or include them in your distribution.</p>
    </div>
    </body>
    </html>
    <?php

    exit;
}
