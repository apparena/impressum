<?php
$aa_inst_id = "";
if (isset($_GET['aa_inst_id'])) {
    $aa_inst_id = $_GET['aa_inst_id'];
} else {
    if (isset($_POST['aa_inst_id'])) {
        $aa_inst_id = $_POST['aa_inst_id'];
    }
}
$object = "questions";
if (isset($_GET['object'])) {
    $object = $_GET['object'];
} else {
    if (isset($_POST['object'])) {
        $object = $_POST['object'];
    }
}
$answer = "";
if (isset($_GET['answer'])) {
    $answer = $_GET['answer'];
} else {
    if (isset($_POST['answer'])) {
        $answer = $_POST['answer'];
    }
}


include_once '../../init.php';
$question = "1";
$aa_inst_id = $_GET["aa_inst_id"];
$share_image_url = $aa['config']['share_image_url']['value'];
$share_title = $aa['config']['question_' . $question]['value'];
$share_desc = __t("post_answer_test", $answer);

$url = "https://apps.facebook.com/" . $aa['instance']['fb_app_url'] . "/modules/open_graph/object.php?aa_inst_id=" . $aa['instance']['aa_inst_id'];
$action_url = "https://apps.facebook.com/" . $aa['instance']['fb_app_url'] . "/modules/open_graph/object_link.php?aa_inst_id=" . $aa['instance']['aa_inst_id'];
$namespace = $aa['instance']['fb_app_url'];
$redirect_url = $aa['instance']['fb_page_url'] . "?sk=app_" . $aa['instance']['fb_app_id'];
$is_correct = __t("is_correct")
?>
<html>
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# <?php echo $namespace; ?>: http://ogp.me/ns/fb/<?php echo $namespace; ?>#">

    <meta property="fb:app_id" content="<?php echo $aa['instance']['fb_app_id']; ?>"/>
    <meta property="og:type" content="<?=$namespace . ':' . $object?>"/>
    <meta property="og:aa_inst_id" content="<?php echo $aa['instance']['aa_inst_id']; ?>"/>
    <meta property="og:url" content="<?php echo $url; ?>"/>
    <meta property="og:title" content=" <?php echo $share_title?>"/>
    <meta property="og:description" content="<?php echo $share_desc; ?>"/>
    <meta property="og:image" content="<?php echo $share_image_url; ?>"/>

    <meta property="aa_quiz_app:question" content="<?=$question_1?>"/>
    <meta property="aa_quiz_app:link" content="<?=$action_url?>&answer=right"/>
    <meta property="aa_quiz_app:link" content="<?=$action_url?>&answer=wrong"/>
    <meta property="aa_quiz_app:text" content="<?=$is_correct?>"/>
</head>
<body>

<script type="text/javascript">
    //top.location = "<?=$redirect_url?>";
</script>

</body>
</html>