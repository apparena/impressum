<?php
$aa_inst_id = "";
if (isset($_GET['aa_inst_id'])) {
    $aa_inst_id = $_GET['aa_inst_id'];
} else {
    if (isset($_POST['aa_inst_id'])) {
        $aa_inst_id = $_POST['aa_inst_id'];
    }
}

include_once '../../init.php';

$answer = __t("yes");
if (isset($_GET['answer'])) {
    $answer = $_GET['answer'];
    if ($answer == "right") {
        $answer = __t("yes");
    }
    if ($answer == "wrong") {
        $answer = __t("no");
    }
}

$aa_inst_id = $_GET["aa_inst_id"];
$share_image_url = $aa['config']['question_1_image_url']['value'];
$share_desc = $aa['config']['question_1']['value'];

$url = "https://apps.facebook.com/" . $aa['instance']['fb_app_url'] . "/modules/open_graph/object.php?aa_inst_id=" . $aa['instance']['aa_inst_id'];
$namespace = $aa['instance']['fb_app_url'];
$redirect_url = $aa['instance']['fb_page_url'] . "?sk=app_" . $aa['instance']['fb_app_id'];

?>
<html>
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# website: http://ogp.me/ns/website#">

    <meta property="fb:app_id" content="<?php echo $aa['instance']['fb_app_id']; ?>"/>
    <meta property="og:type" content="website"/>
    <meta property="og:url" content="<?php echo $url; ?>"/>
    <meta property="og:description" content="<?php echo $share_desc; ?>"/>
    <meta property="og:title" content="<?=$answer?>"/>
    <meta property="og:image" content="<?=$share_image_url?>"/>
</head>
<body>


<script type="text/javascript">
    top.location = "<?=$redirect_url?>";
</script>

</body>
</html>