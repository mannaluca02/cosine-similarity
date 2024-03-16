<?php
$style = "display: none;";
$input = ['Die Quadratwurzel aus 100 ist eine rationale Zahl, nämlich 10.', 'Die Goldene Regel der Algebra: Gleichungen auf beiden Seiten vereinfachen.', 'Integralrechnung ermöglicht die Berechnung von Flächen unter komplexen Kurven.', 'In der Statistik helfen Standardabweichung und Varianz bei Datenanalysen.', 'Ein regelmäßiges Polygon hat gleich lange Seiten und Innenwinkel.', 'Eulers Formel verbindet komplexe Exponentialfunktionen mit Trigonometrie und Algebra.', 'Binomische Formeln erleichtern das Ausmultiplizieren von binomischen Ausdrücken.', 'Die Kettenregel in der Differentialrechnung ermöglicht Ableitungen von zusammengesetzten Funktionen.', 'Die Zahl Pi repräsentiert das Verhältnis von Umfang zu Durchmesser.', 'Mathematik ist das Tor zu abstraktem Denken und Kreativität.'];
$given = $input;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sentence = $_POST['sentence'];
    array_push($given, $sentence);

    // Checke ob das eingegebene einen gültigen Wert hat
    if(checkInput($sentence)){
        $style = "display: block;";
    };

    // Erstellt ein array mit allen Wörtern
    $words = createWordArray($given);

    // Erstellt ein 2D-Array aus den gegebenen Daten und Wörtern
    $temp = create2DArray($given, $words);
}
function checkInput($input)
{
    $input = preg_replace('/[.,:0-9]/', '', $input);
    if ($input == "") {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Fehler</strong> Das Eingegebene Wort oder Satz hat entweder keinen Wert oder besteht nur aus Sonderzeichen
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>';
        return false;
    }
    return true;
}
// Dieses array schreibt aus jedem Satz die Wörter in ein array
function createWordArray($given)
{
    $words = [];
    foreach ($given as $sentence) {
        $sentence = strtolower($sentence);
        $sentence = preg_replace('/[.,:0-9]/', '', $sentence);
        $wordsInSentence = preg_split('/\s+/', $sentence, -1, PREG_SPLIT_NO_EMPTY);
        $words = array_merge($words, $wordsInSentence);
    }
    $words = array_unique($words);
    $words = array_values($words);
    return $words;
}

// Dieses array schreibt aus jedem Satz die Wörter in ein array und zählt diese. Dieses array beinhaltet alle Vektoren
function create2DArray($given, $words)
{
    $result = [];
    foreach ($given as $sentence) {
        $sentence = strtolower($sentence);
        $sentence = preg_replace('/[.,:0-9]/', '', $sentence);
        $wordsInSentence = preg_split('/\s+/', $sentence, -1, PREG_SPLIT_NO_EMPTY);
        $intersect = array_intersect($wordsInSentence, $words);
        $countedWords = array_count_values($intersect);
        $allWords = array_fill_keys($words, 0);
        $mergedWords = array_merge($allWords, $countedWords);
        $result[] = $mergedWords;
    }
    return $result;
}

// dot product
function dotp($arr1, $arr2)
{
    return array_sum(array_map(function ($a, $b) {
        return $a * $b;
    }, $arr1, $arr2));
}

function cosineSim($arr1, $arr2)
{
    return dotp($arr1, $arr2) / sqrt(dotp($arr1, $arr1) * dotp($arr2, $arr2));
}

function createComparisonTable($array2D)
{
    echo '<div class="table-responsive">';
    echo '<table>';
    // Kopfzeile hinzufügen
    echo '<tr><th></th>';
    for ($i = 0; $i < count($array2D); $i++) {
        echo '<th>Satz ' . ($i + 1) . '</th>';
    }
    echo '</tr>';

    for ($i = 0; $i < count($array2D); $i++) {
        echo '<tr>';
        // Linke Spalte hinzufügen
        echo '<th>Satz ' . ($i + 1) . '</th>';
        for ($j = 0; $j < count($array2D); $j++) {
            $cosineSimilarity = cosineSim($array2D[$i], $array2D[$j]);
            echo '<td>' . $cosineSimilarity . '</td>';
        }
        echo '</tr>';
    }
    echo '</table>';
    echo '</div>';
}
?>

<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Cosine sim</title>
    <style>
        table {
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        tr:last-child,
        td:last-child {
            background-color: #FF6663;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col text-center" style="margin-top: 5rem; margin-bottom: 3rem;">

                <h1>Kosinus Ähnlichkeit</h1>
                <p>
                    Auf dieser Webseite wird die Kosinus Ähnlichkeit mit einem praktischem beispiel erklärt für die IDPA von Giulia Ferraina, Dani Hertzka und Luca Manna.
                </p>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col mb-3">
                <h2>Folgende Sätzte sind gegeben:</h2>
                <?php
                echo '<ol>';
                for ($i = 0; $i < count($input); $i++) {
                    echo '<li>' . $input[$i] . '</li>';
                }
                echo '</ol>';
                ?>
                <form id="myForm" action="" method="post">
                    <label for="sentence">Geben Sie ein Wort oder Satz ein, um die Ähnlichkeiten zu überprüfen.</label>
                    <div class="input-group">
                        <input type="text" name="sentence" id="sentence" class="form-control" aria-label="Geben Sie ein Wort oder Satz ein, um die Ähnlichkeiten zu überprüfen.">
                        <button class="btn btn-outline-secondary" type="button submit" id="senden">Suche Ähnlichkeit</button>
                    </div>
                </form>
            </div>
            <div id="nichtAnzeigen" style="<?php echo $style ?>">
                <div id="col mb-3">
                    <h2>Vorbereitung Ähnlichkeit</h2>
                    <p>Nun wird ein Vektor erstellt, welcher alle vokommenden Wörter beinhaltet. Um die Suche später genauer zu machen werden in diesem Beispiel Zahlen und Sonderzeichen ausgelassen. Alle Buchstaben werden zudem klein geschrieben</p>
                    <pre>
                    <?php
                    print_r($words);
                    ?>
                </pre>
                </div>
                <div id="col mb-3">
                    <h2>Vektoren erstellen</h2>
                    <p>Nun wird aus jedem Satz ein Vektor erstellt. Dabei ist die anzahl Dimensionen gleich der Anzahl vorkommender Wörter. Jedes Element des Vektors steht für ein Wort. D.h. wenn dass wort "in" 2 Mal vorkommt wird bei dem Vektor das Element bei dem Index 26 gleich 2 sein. Kommt das wort "quadratwurzel" kein einziges Mal in dem Satz vor, so wird das Element bei dem Index 1 gleich 0 sein.</p>
                    <p>Hier wird der folgende Satz mit dieser Schreibweise dargestellt: "Integralrechnung ermöglicht die Berechnung von Flächen unter komplexen Kurven."</p>
                    <pre>
                    <?php
                    print_r($temp[2]);
                    ?>
                </pre>

                </div>
                <div id="col mb-3">
                    <h2>Vergleich aller Vektoren</h2>
                    <p>Vergleichen wir nun alle vektoren erhalten wir eine Matrix. Diese sieht wiefolgt aus.</p>
                    <?php
                    echo createComparisonTable($temp);
                    $sim = [];
                    for ($j = 0; $j < count($temp) - 1; $j++) {
                        array_push($sim, cosineSim($temp[10], $temp[$j]));
                    }
                    ?>
                    <p>In der Matrix ist die unterste Zeile und die rechte Spalte (roter hintergrund), das mitgegebene Wort oder Satz. Aus diesem grund erhalten wir auch die grösste Ähnlichkeit, nähmlich 1, unten rechts, da wir die identischen Vektoren miteinander vergleichen. Suchen wir die 2. grösste Ähnlichkeit so erhalten wir:
                        <?php
                        echo max($sim);
                        if (max($sim) == 0) {
                            echo '. Dies bedeutet es gibt keine Ähnlichkeit mit einem anderen Satz';
                        } else {
                            echo '. Dies bedeutet es gibt eine Ähnlichkeit mit dem Satz: ';
                            echo $input[array_search(max($sim), $sim)];
                        }
                        ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</body>

</html>