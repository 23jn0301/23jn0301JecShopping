<?php 
    require_once './helpers/MemberDAO.php';

    $email="";
    $errs=[];

    //セッションの開始
    session_start();

    //POSTメソッドでリクエストされたとき
    if($_SERVER['REQUEST_METHOD']==='POST'){
        //入力されたメールアドレスとパスワードを受け取る
        $email=$_POST['email'];
        $password=$_POST['password'];

        //メールアドレスのバリデーションチェック
        if($email===''){
            $errs[]='メールアドレスを入力してください';
        }
        else if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
            $errs[]='メールアドレスの形式に誤りがあります。';
        }
        //パスワードの未入力チェック
        if($password===''){
            $errs[]='パスワードを入力してください。';
        }
        //エラーのセット確認はemptyで行う(空であるか)
        if (empty($errs)) {
        //DBからメールアドレス・パスワードが一致する会員データを取り出す
        $memberDAO=new MemberDAO();
        $member=$memberDAO->get_member($email,$password);

        //会員データが取り出せたとき
        if($member!=false){
            //セッションIDを変更する
            session_regenerate_id(true);

            //セッション変数に会員データを保存する
            $_SESSION['member']=$member;

            //index.phpに移動
            header('Location:index.php');
            exit();
        }
        //会員データが取り出せなかったとき
        else{
            $errs[]='メールアドレスまたはパスワードに誤りがあります。';
        }
    }
    }
    ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>ログイン</title>
    <link href="css/LoginStyle.css" rel="stylesheet">
</head>
<body>
    <?php include "header2.php"?>
    <form action="" method="POST">
        <table id="LoginTable" class="box";>
            <tr>
                <th colspan="2">
                    ログイン
                </th>
            </tr>
            <tr>
                <td>メールアドレス</td>
                <td>
                    <!--requiredにより@が含まれていないときの表示-->
                    <!--autofocusによりページを開いたときに設定したテキストを自動選択-->
                    <input type="email"name="email"required autofocus>
                </td>
            </tr>
            <tr>
                <td>パスワード</td>
                <td>
                    <input type="password"name="password" required > 
                </td>
            </tr>
            <tr>
                <td>
                    <input type="submit"value="ログイン">
                </td>
                <td>
                    <?php foreach($errs as $e):?>
                        <span style="color:red"><?=$e ?></span>
                        <br>
                    <?php endforeach; ?>
                </td>
            </tr>
        </table>
    </form>

    <table class="box">
        <tr>
            <th>初めてご利用の方</th>
        </tr>
        <tr>
            <td>ログインするには会員登録が必要です。</td>
        </tr>
        <tr>
            <td><a href="signup.php">新規会員登録はこちら</a></td>
        </tr>
    </table> 
</body>
</html>
