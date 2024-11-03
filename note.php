<?php


ini_set("display_errors", 0);
ini_set("display_startup_errors", 0);
error_reporting(32767);
$file = "./api/note.json";
$jsondata = file_get_contents($file);
$json = json_decode($jsondata, true);
$title1 = $json["title"];
$content1 = $json["content"];
$message = "<div class=\"alert alert-primary\" id=\"flash-msg\"><h4><i class=\"icon fa fa-check\"></i>Items Updated!</h4></div>";
if (isset($_POST["text"])) {
    $replacementData = ["title" => $_POST["title"], "content" => $_POST["content"]];
    $newArrayData = array_replace_recursive($json, $replacementData);
    $newJsonData = json_encode($newArrayData, JSON_UNESCAPED_UNICODE);
    file_put_contents($file, $newJsonData);
    header("Location: note.php?m=" . $message);
}
include "includes/header.php";
echo "        <div class=\"container-fluid\">\n\n";
if (isset($_GET["m"])) {
    echo $_GET["m"];
}
echo "          <h1 class=\"h3 mb-1 text-gray-800\">Notifications</h1>\n         \n          <!-- Content Row -->\n          <div class=\"row\">\n\n            <!-- First Column -->\n            <div class=\"col-lg-12\">\n\n              <!-- Custom codes -->\n                <div class=\"card border-left-primary shadow h-100 card shadow mb-4\">\n                <div class=\"card-header py-3\">\n                <h6 class=\"m-0 font-weight-bold text-primary\"><i class=\"fa fa-bullhorn\"></i> Send Notifications</h6>\n                </div>\n                <div class=\"card-body\">\n                         <form method=\"post\">\n                              <div class=\"form-group \">\n                                       \n                                        <label class=\"control-label\" for=\"title\">\n                                        <strong>Title:</strong>\n                                    </label>\n                                    <div class=\"input-group\">\n";
echo "\t\t\t\t\t\t\t\t\t<input type=\"text\" class=\"form-control\" placeholder=\"TITLE\" name=\"title\" value=\"" . $title1 . "\">" . "\n";
echo "                                    </div>\n                                </div>\n                                <div class=\"form-group \">\n                                    <label class=\"control-label \" for=\"content\">\n                                    <strong>Message:</strong>\n                                    </label>\n                                    <div class=\"input-group\">\n";
echo "\t\t\t\t\t\t\t\t\t<input type=\"text\" class=\"form-control\" placeholder=\"MESSAGE\" name=\"content\" value=\"" . $content1 . "\">" . "\n";
echo "                                    </div>\n                                </div>\n\t\t\t\t\t\t\t    <button type=\"submit\" name=\"text\" class=\"btn btn-primary\">Update</button>\n\t\t\t\t\t\t\t     </div>\n\t\t\t\t\t\t\t\n\t\t\t\t\t\t</form>\n\t\t\t\t\t\t</div>\n                                </div>\n                                </div>\n                               \n                                </div>\n\t\t\t\t\t\t\n</html>\n\n";
include "includes/footer.php";
echo "</body>\n  <script> \n\$(document).ready(function () {\n    \$(\"#flash-msg\").delay(3000).fadeOut(\"slow\");\n});\n  </script>\n</html>";

?>