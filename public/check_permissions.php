<?php
// Vérification des permissions et configurations PHP
echo "<h1>Diagnostic des uploads PHP</h1>";

// Paramètres PHP
echo "<h2>Configuration PHP</h2>";
echo "<ul>";
echo "<li>upload_max_filesize: " . ini_get('upload_max_filesize') . "</li>";
echo "<li>post_max_size: " . ini_get('post_max_size') . "</li>";
echo "<li>upload_tmp_dir: " . ini_get('upload_tmp_dir') . "</li>";
echo "</ul>";

// Vérification du dossier temporaire
$tmpDir = ini_get('upload_tmp_dir') ?: sys_get_temp_dir();
echo "<h2>Dossier temporaire</h2>";
echo "<p>Chemin: $tmpDir</p>";

if (file_exists($tmpDir)) {
    echo "<p style='color:green'>✓ Le dossier existe</p>";
    
    if (is_writable($tmpDir)) {
        echo "<p style='color:green'>✓ Le dossier est accessible en écriture</p>";
    } else {
        echo "<p style='color:red'>✗ Le dossier n'est PAS accessible en écriture</p>";
    }
} else {
    echo "<p style='color:red'>✗ Le dossier n'existe PAS</p>";
}

// Vérification du dossier de destination
$targetDir = __DIR__ . '/Images';
echo "<h2>Dossier de destination</h2>";
echo "<p>Chemin: $targetDir</p>";

if (file_exists($targetDir)) {
    echo "<p style='color:green'>✓ Le dossier existe</p>";
    
    if (is_writable($targetDir)) {
        echo "<p style='color:green'>✓ Le dossier est accessible en écriture</p>";
    } else {
        echo "<p style='color:red'>✗ Le dossier n'est PAS accessible en écriture</p>";
    }
    
    // Essai d'écriture
    $testFile = $targetDir . '/test_' . uniqid() . '.txt';
    if (@file_put_contents($testFile, 'Test write permissions')) {
        echo "<p style='color:green'>✓ Test d'écriture réussi</p>";
        @unlink($testFile);
    } else {
        echo "<p style='color:red'>✗ Test d'écriture échoué</p>";
    }
} else {
    echo "<p style='color:red'>✗ Le dossier n'existe PAS</p>";
    
    // Essai de création
    if (@mkdir($targetDir, 0777, true)) {
        echo "<p style='color:green'>✓ Dossier créé avec succès</p>";
    } else {
        echo "<p style='color:red'>✗ Impossible de créer le dossier</p>";
    }
}