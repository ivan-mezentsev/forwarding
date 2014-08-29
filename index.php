<?php
/*
 * Created on 13.08.2014, 01 PM
 * 
 * Copyright (c) 2014, Ivan Mezentsev
 */

include('/etc/forwarding_config.php');

################################################################################  
@mysql_query("Set charset utf8");
@mysql_query("Set character_set_client = utf8");
@mysql_query("Set character_set_connection = utf8");
@mysql_query("Set character_set_results = utf8");
@mysql_query("Set collation_connection = utf8_general_ci");
@mysql_query("set names utf8 collate utf8_general_ci");

@mysql_connect("Set charset utf8");
@mysql_connect("Set character_set_client = utf8");
@mysql_connect("Set character_set_connection = utf8");
@mysql_connect("Set character_set_results = utf8");
@mysql_connect("Set collation_connection = utf8_general_ci");
@mysql_connect("set names utf8 collate utf8_general_ci");

// Соединяемся с сервером базы данных
$dbcnx = @mysql_connect($dblocation, $dbuser, $dbpasswd);
if (!$dbcnx) {
    echo "error!";
    exit();
}
// Выбираем базу данных
if (!@mysql_select_db($dbname, $dbcnx)) {
    echo "error!";
    exit();
}
################################################################################  


if ($_GET['action'] == "")
    $_GET['action'] = "show";


if ($_GET['action'] == "show") {
    ?>
    <table align="center" border="0" width="90%" frame="void">
        <tr><td colspan="6" align="center"><B>Управление перенаправлением вызовов (Asterisk IP PBX)</B></td></tr></table>
    <table align="center" border="1" width="90%" frame="void" bgcolor="#f4f4f4">
        <tr bgcolor="silver">
            <td><CITE>Внутр. номер</CITE></td><td><CITE>Безусловная</CITE></td><td><CITE>По-недоступности</CITE></td><td><CITE>По-занятости</CITE></td><td><CITE>По-неответу</CITE></td>
        </tr>

        <?
        $tot = mysql_query("SELECT * FROM forwarding ORDER BY ext;");
        while ($themes = mysql_fetch_array($tot)) {
            // Вытаскиваем переменные из базы данных
            $id = trim($themes['id']);
            $ext = trim($themes['ext']);
            $fwd_always_state = trim($themes['fwd_always_state']);
            $fwd_always_dst = trim($themes['fwd_always_dst']);
            $fwd_not_available_state = trim($themes['fwd_not_available_state']);
            $fwd_not_available_dst = trim($themes['fwd_not_available_dst']);
            $fwd_busy_state = trim($themes['fwd_busy_state']);
            $fwd_busy_dst = trim($themes['fwd_busy_dst']);
            $fwd_no_answer_state = trim($themes['fwd_no_answer_state']);
            $fwd_no_answer_time = trim($themes['fwd_no_answer_time']);
            $fwd_no_answer_dst = trim($themes['fwd_no_answer_dst']);

            if ($fwd_always_state == "1")
                $fwd_always_state_html = "checked=\"checked\"";
            else
                $fwd_always_state_html = "";
            if ($fwd_not_available_state == "1")
                $fwd_not_available_state_html = "checked=\"checked\"";
            else
                $fwd_not_available_state_html = "";
            if ($fwd_busy_state == "1")
                $fwd_busy_state_html = "checked=\"checked\"";
            else
                $fwd_busy_state_html = "";
            if ($fwd_no_answer_state == "1")
                $fwd_no_answer_state_html = "checked=\"checked\"";
            else
                $fwd_no_answer_state_html = "";
            ?>
            <tr>
                <td><? print "<a href=\"?action=edit&item=$id\">$ext</a>" ?></td>
                <td><? print "<input type=\"checkbox\" disabled=\"disabled\" $fwd_always_state_html name=\"fwd_always_state\" value=\"$fwd_always_state\">$fwd_always_dst"; ?></td>
                <td><? print "<input type=\"checkbox\" disabled=\"disabled\" $fwd_not_available_state_html name=\"fwd_not_available_state\" value=\"$fwd_not_available_state\">$fwd_not_available_dst"; ?></td>
                <td><? print "<input type=\"checkbox\" disabled=\"disabled\" $fwd_busy_state_html name=\"fwd_busy_state\" value=\"$fwd_busy_state\">$fwd_busy_dst"; ?></td>
                <td><? print "<input type=\"checkbox\" disabled=\"disabled\" $fwd_no_answer_state_html name=\"fwd_no_answer_state\" value=\"$fwd_no_answer_state\">$fwd_no_answer_dst ($fwd_no_answer_time сек.)"; ?></td>
            </tr>
        <?
    }
    ?>
    </table>
    </table><table align="center" border="0" width="90%" frame="void"><tr><td></td></tr></table>
    <table align="center" border="0" width="90%" frame="void">
        <tr align="center">
            <td><a href="?action=add"><button>Добавить</button></a></td>
        </tr>
    </table>
    <?
} //Конец Секция отображения

if ($_GET['action'] == "edit" AND $_GET['item'] != "") {
    $update_now = $_POST["update_now"];
    if (empty($update_now)) {
        $id = $_GET['item'];
        $id = $_GET['item'];
        if (!ctype_digit($id)) {
            header("Location: ?action=show");
        }
        $themes = mysql_fetch_array(mysql_query("SELECT * FROM forwarding WHERE id = '" . "$id" . "';"), 1);
        $ext = trim($themes['ext']);
        $fwd_always_state = trim($themes['fwd_always_state']);
        $fwd_always_dst = trim($themes['fwd_always_dst']);
        $fwd_not_available_state = trim($themes['fwd_not_available_state']);
        $fwd_not_available_dst = trim($themes['fwd_not_available_dst']);
        $fwd_busy_state = trim($themes['fwd_busy_state']);
        $fwd_busy_dst = trim($themes['fwd_busy_dst']);
        $fwd_no_answer_state = trim($themes['fwd_no_answer_state']);
        $fwd_no_answer_time = trim($themes['fwd_no_answer_time']);
        $fwd_no_answer_dst = trim($themes['fwd_no_answer_dst']);
        if ($fwd_always_state == "1")
            $fwd_always_state_html = "checked=\"checked\"";
        if ($fwd_not_available_state == "1")
            $fwd_not_available_state_html = "checked=\"checked\"";
        if ($fwd_busy_state == "1")
            $fwd_busy_state_html = "checked=\"checked\"";
        if ($fwd_no_answer_state == "1")
            $fwd_no_answer_state_html = "checked=\"checked\"";
    }
    if (!empty($update_now)) {
        $id = $_GET['item'];
        if (!ctype_digit($id)) {
            header("Location: ?action=show");
        }
        $themes = mysql_fetch_array(mysql_query("SELECT * FROM forwarding WHERE id = '" . "$id" . "';"), 1);
        $ext = trim($themes['ext']);

        $fwd_always_state = $_POST["fwd_always_state"];
        $fwd_always_dst = $_POST["fwd_always_dst"];
        if (isset($fwd_always_state)) {
            if (!ctype_digit($fwd_always_dst)) {
                $Color1begin = "<FONT COLOR=\"RED\">";
                $Color1end = "</FONT>";
                $update_now = "";
            }
        }

        $fwd_not_available_state = $_POST["fwd_not_available_state"];
        $fwd_not_available_dst = $_POST["fwd_not_available_dst"];
        if (isset($fwd_not_available_state)) {
            if (!ctype_digit($fwd_not_available_dst)) {
                $Color2begin = "<FONT COLOR=\"RED\">";
                $Color2end = "</FONT>";
                $update_now = "";
            }
        }

        $fwd_busy_state = $_POST["fwd_busy_state"];
        $fwd_busy_dst = $_POST["fwd_busy_dst"];
        if (isset($fwd_busy_state)) {
            if (!ctype_digit($fwd_busy_dst)) {
                $Color3begin = "<FONT COLOR=\"RED\">";
                $Color3end = "</FONT>";
                $update_now = "";
            }
        }

        $fwd_no_answer_state = $_POST["fwd_no_answer_state"];
        $fwd_no_answer_time = $_POST["fwd_no_answer_time"];
        $fwd_no_answer_dst = $_POST["fwd_no_answer_dst"];

        if (isset($fwd_no_answer_state)) {
            if (!ctype_digit($fwd_no_answer_time)) {
                $Color4begin = "<FONT COLOR=\"RED\">";
                $Color4end = "</FONT>";
                $update_now = "";
            }
            if ($fwd_no_answer_time > 120 OR $fwd_no_answer_time < 1) {
                $Color4begin = "<FONT COLOR=\"RED\">";
                $Color4end = "</FONT>";
                $update_now = "";
            }
            if (!ctype_digit($fwd_no_answer_dst)) {
                $Color5begin = "<FONT COLOR=\"RED\">";
                $Color5end = "</FONT>";
                $update_now = "";
            }
        }

        if (isset($fwd_always_state))
            $fwd_always_state_html = "checked=\"checked\"";
        if (isset($fwd_not_available_state))
            $fwd_not_available_state_html = "checked=\"checked\"";
        if (isset($fwd_busy_state))
            $fwd_busy_state_html = "checked=\"checked\"";
        if (isset($fwd_no_answer_state))
            $fwd_no_answer_state_html = "checked=\"checked\"";

        if (!empty($update_now)) {

            #Якобы всё валидно и можно обновлять в DB
            if (isset($fwd_always_state))
                $fwd_always_state = "1";
            else
                $fwd_always_state = "0";
            if (isset($fwd_not_available_state))
                $fwd_not_available_state = "1";
            else
                $fwd_not_available_state = "0";
            if (isset($fwd_busy_state))
                $fwd_busy_state = "1";
            else
                $fwd_busy_state = "0";
            if (isset($fwd_no_answer_state))
                $fwd_no_answer_state = "1";
            else
                $fwd_no_answer_state = "0";

            $query = "UPDATE forwarding SET fwd_always_state = '" . $fwd_always_state .
                    "', fwd_always_dst = '" . $fwd_always_dst .
                    "', fwd_not_available_state = '" . $fwd_not_available_state .
                    "', fwd_not_available_dst = '" . $fwd_not_available_dst .
                    "', fwd_busy_state = '" . $fwd_busy_state .
                    "', fwd_busy_dst = '" . $fwd_busy_dst .
                    "', fwd_no_answer_state = '" . $fwd_no_answer_state .
                    "', fwd_no_answer_time = '" . $fwd_no_answer_time .
                    "', fwd_no_answer_dst = '" . $fwd_no_answer_dst .
                    "' WHERE id = '" . $id . "'";

            if (!mysql_query($query)) {
                echo "error!";
                exit();
            }
            if ($fwd_always_state == "1" OR $fwd_not_available_state == "1" OR $fwd_busy_state == "1" OR $fwd_no_answer_state == "1") {
                astdb("put", "Forwarding", "$ext" . "_fwd_always_state", $fwd_always_state);
                astdb("put", "Forwarding", "$ext" . "_fwd_always_dst", $fwd_always_dst);
                astdb("put", "Forwarding", "$ext" . "_fwd_not_available_state", $fwd_not_available_state);
                astdb("put", "Forwarding", "$ext" . "_fwd_not_available_dst", $fwd_not_available_dst);
                astdb("put", "Forwarding", "$ext" . "_fwd_busy_state", $fwd_busy_state);
                astdb("put", "Forwarding", "$ext" . "_fwd_busy_dst", $fwd_busy_dst);
                astdb("put", "Forwarding", "$ext" . "_fwd_no_answer_state", $fwd_no_answer_state);
                astdb("put", "Forwarding", "$ext" . "_fwd_no_answer_time", $fwd_no_answer_time);
                astdb("put", "Forwarding", "$ext" . "_fwd_no_answer_dst", $fwd_no_answer_dst);
            } else {
                astdb("del", "Forwarding", "$ext" . "_fwd_always_state");
                astdb("del", "Forwarding", "$ext" . "_fwd_always_dst");
                astdb("del", "Forwarding", "$ext" . "_fwd_not_available_state");
                astdb("del", "Forwarding", "$ext" . "_fwd_not_available_dst");
                astdb("del", "Forwarding", "$ext" . "_fwd_busy_state");
                astdb("del", "Forwarding", "$ext" . "_fwd_busy_dst");
                astdb("del", "Forwarding", "$ext" . "_fwd_no_answer_state");
                astdb("del", "Forwarding", "$ext" . "_fwd_no_answer_time");
                astdb("del", "Forwarding", "$ext" . "_fwd_no_answer_dst");
            }

            header("Location: ?action=show");
        }
    }

    if (empty($update_now)) {
        ?>
        <table align="center" border="0" width="90%" frame="void">
            <tr><td colspan="6" align="center"><B>Редактирование #<? print $ext; ?></B></td></tr></table>

        <form method="post" action="?action=edit&item=<? print $id; ?>">
            <input type=hidden name=update_now value=post>


            <table align="center" cellspacing="2" cellpadding="2" width="450" frame="border" bgcolor="#f4f4f4">
                <tr>
                    <td width="200"><B>Безусловная</B>:</td>
                    <td width="200"><? print "<input type=\"checkbox\" $fwd_always_state_html name=\"fwd_always_state\" value=\"$fwd_always_state\">Включена"; ?></td>
                </tr>
                <tr>
                    <td width="200"><? echo $Color1begin; ?>Номер для переадресации:<? echo $Color1end; ?></td>
                    <td width="200"><input class="input" name="fwd_always_dst" value="<? echo $fwd_always_dst; ?>" /></td>
                </tr>
            </table>

            <table align="center" border="0" width="90%" frame="void"><tr><td></td></tr></table>

            <table align="center" cellspacing="2" cellpadding="2" width="450" frame="border" bgcolor="#f4f4f4">
                <tr>
                    <td width="200"><B>По-недоступности</B>:</td>
                    <td width="200"><? print "<input type=\"checkbox\" $fwd_not_available_state_html name=\"fwd_not_available_state\" value=\"$fwd_not_available_state\">Включена"; ?></td>
                </tr>
                <tr>
                    <td width="200"><? echo $Color2begin; ?>Номер для переадресации:<? echo $Color2end; ?></td>
                    <td width="200"><input class="input" name="fwd_not_available_dst" value="<? echo $fwd_not_available_dst; ?>" /></td>
                </tr>
            </table>

            <table align="center" border="0" width="90%" frame="void"><tr><td></td></tr></table>

            <table align="center" cellspacing="2" cellpadding="2" width="450" frame="border" bgcolor="#f4f4f4">
                <tr>
                    <td width="200"><B>По-занятости</B>:</td>
                    <td width="200"><? print "<input type=\"checkbox\" $fwd_busy_state_html name=\"fwd_busy_state\" value=\"$fwd_busy_state\">Включена"; ?></td>
                </tr>
                <tr>
              <!--   <td width="200"><FONT COLOR="RED">Номер для переадресации:</FONT></td>-->
                    <td width="200"><? echo $Color3begin; ?>Номер для переадресации<? echo $Color3end; ?></td>
                    <td width="200"><input class="input" name="fwd_busy_dst" value="<? echo $fwd_busy_dst; ?>" /></td>
                </tr>
            </table>

            <table align="center" border="0" width="90%" frame="void"><tr><td></td></tr></table>

            <table align="center" cellspacing="2" cellpadding="2" width="450" frame="border" bgcolor="#f4f4f4">
                <tr>
                    <td width="200"><B>По-неответу</B>:</td>
                    <td width="200"><? print "<input type=\"checkbox\" $fwd_no_answer_state_html name=\"fwd_no_answer_state\" value=\"$fwd_no_answer_state\">Включена"; ?></td>
                </tr>
                <tr>
                    <td width="200"><? echo $Color4begin; ?>Время (1-120 сек.):<? echo $Color4end; ?></td>
                    <td width="200"><input class="input" name="fwd_no_answer_time" value="<? echo $fwd_no_answer_time; ?>" /></td>
                </tr>
                <tr>
                    <td width="200"><? echo $Color5begin; ?>Номер для переадресации:<? echo $Color5end; ?></td>
                    <td width="200"><input class="input" name="fwd_no_answer_dst" value="<? echo $fwd_no_answer_dst; ?>" /></td>

                </tr>
            </table>
            <table align="center" border="0" width="90%" frame="void"><tr><td></td></tr></table>
            <table align="center" border="0"  width="450" frame="void">
                <tr align="center" valign="top">
                    <td><button type="submit">Сохранить</button></td>  
                    <td><button type="reset">Сбросить</button></form></td>
                    <td>
                        <form method="post" action="?action=del&item=<? print $id; ?>">
                            <input type="submit" value="Удалить">
                        </form>
                    </td>
                    <td>
                        <form method="post" action="?action=show">
                            <input type="submit" value="Отменить">
                        </form>
                    </td>
                </tr>
            </table>
            <?
        }
    } //Конец Секция редактирования конкретного напрвления

    if ($_GET['action'] == "add") {
        $update_now = $_POST["update_now"];

        if (!empty($update_now)) {

            $ext = $_POST["ext"];
            if (!ctype_digit($ext)) {
                $Color0begin = "<FONT COLOR=\"RED\">";
                $Color0end = "</FONT>";
                $update_now = "";
            } else {

                $themes = mysql_fetch_array(mysql_query("SELECT ext FROM forwarding WHERE ext = '" . "$ext" . "';"), 1);
                $extdb = trim($themes['ext']);
                if (!empty($extdb)) {
                    $Color0begin = "<FONT COLOR=\"RED\">";
                    $Color0end = "</FONT>";
                    $update_now = "";
                }
            }

            $fwd_always_state = $_POST["fwd_always_state"];
            $fwd_always_dst = $_POST["fwd_always_dst"];
            if (isset($fwd_always_state)) {
                if (!ctype_digit($fwd_always_dst)) {
                    $Color1begin = "<FONT COLOR=\"RED\">";
                    $Color1end = "</FONT>";
                    $update_now = "";
                }
            }

            $fwd_not_available_state = $_POST["fwd_not_available_state"];
            $fwd_not_available_dst = $_POST["fwd_not_available_dst"];
            if (isset($fwd_not_available_state)) {
                if (!ctype_digit($fwd_not_available_dst)) {
                    $Color2begin = "<FONT COLOR=\"RED\">";
                    $Color2end = "</FONT>";
                    $update_now = "";
                }
            }

            $fwd_busy_state = $_POST["fwd_busy_state"];
            $fwd_busy_dst = $_POST["fwd_busy_dst"];
            if (isset($fwd_busy_state)) {
                if (!ctype_digit($fwd_busy_dst)) {
                    $Color3begin = "<FONT COLOR=\"RED\">";
                    $Color3end = "</FONT>";
                    $update_now = "";
                }
            }

            $fwd_no_answer_state = $_POST["fwd_no_answer_state"];
            $fwd_no_answer_time = $_POST["fwd_no_answer_time"];
            $fwd_no_answer_dst = $_POST["fwd_no_answer_dst"];

            if (isset($fwd_no_answer_state)) {
                if (!ctype_digit($fwd_no_answer_time)) {
                    $Color4begin = "<FONT COLOR=\"RED\">";
                    $Color4end = "</FONT>";
                    $update_now = "";
                }
                if ($fwd_no_answer_time > 120 OR $fwd_no_answer_time < 1) {
                    $Color4begin = "<FONT COLOR=\"RED\">";
                    $Color4end = "</FONT>";
                    $update_now = "";
                }
                if (!ctype_digit($fwd_no_answer_dst)) {
                    $Color5begin = "<FONT COLOR=\"RED\">";
                    $Color5end = "</FONT>";
                    $update_now = "";
                }
            }

            if (isset($fwd_always_state))
                $fwd_always_state_html = "checked=\"checked\"";
            if (isset($fwd_not_available_state))
                $fwd_not_available_state_html = "checked=\"checked\"";
            if (isset($fwd_busy_state))
                $fwd_busy_state_html = "checked=\"checked\"";
            if (isset($fwd_no_answer_state))
                $fwd_no_answer_state_html = "checked=\"checked\"";

            if (!empty($update_now)) {

                #Якобы всё валидно и можно обновлять в DB
                if (isset($fwd_always_state))
                    $fwd_always_state = "1";
                else
                    $fwd_always_state = "0";
                if (isset($fwd_not_available_state))
                    $fwd_not_available_state = "1";
                else
                    $fwd_not_available_state = "0";
                if (isset($fwd_busy_state))
                    $fwd_busy_state = "1";
                else
                    $fwd_busy_state = "0";
                if (isset($fwd_no_answer_state))
                    $fwd_no_answer_state = "1";
                else
                    $fwd_no_answer_state = "0";

                $query = "INSERT INTO forwarding VALUES (0,
                                        '$ext',
                                        '$fwd_always_state',
                                        '$fwd_always_dst',
                                        '$fwd_not_available_state',
                                        '$fwd_not_available_dst',
                                        '$fwd_busy_state',
                                        '$fwd_busy_dst',
                                        '$fwd_no_answer_state',
                                        '$fwd_no_answer_time',
                                        '$fwd_no_answer_dst');";

                if (!mysql_query($query)) {
                    echo "error!";
                    exit();
                }

                if ($fwd_always_state == "1" OR $fwd_not_available_state == "1" OR $fwd_busy_state == "1" OR $fwd_no_answer_state == "1") {
                    astdb("put", "Forwarding", "$ext" . "_fwd_always_state", $fwd_always_state);
                    astdb("put", "Forwarding", "$ext" . "_fwd_always_dst", $fwd_always_dst);
                    astdb("put", "Forwarding", "$ext" . "_fwd_not_available_state", $fwd_not_available_state);
                    astdb("put", "Forwarding", "$ext" . "_fwd_not_available_dst", $fwd_not_available_dst);
                    astdb("put", "Forwarding", "$ext" . "_fwd_busy_state", $fwd_busy_state);
                    astdb("put", "Forwarding", "$ext" . "_fwd_busy_dst", $fwd_busy_dst);
                    astdb("put", "Forwarding", "$ext" . "_fwd_no_answer_state", $fwd_no_answer_state);
                    astdb("put", "Forwarding", "$ext" . "_fwd_no_answer_time", $fwd_no_answer_time);
                    astdb("put", "Forwarding", "$ext" . "_fwd_no_answer_dst", $fwd_no_answer_dst);
                } else {
                    astdb("del", "Forwarding", "$ext" . "_fwd_always_state");
                    astdb("del", "Forwarding", "$ext" . "_fwd_always_dst");
                    astdb("del", "Forwarding", "$ext" . "_fwd_not_available_state");
                    astdb("del", "Forwarding", "$ext" . "_fwd_not_available_dst");
                    astdb("del", "Forwarding", "$ext" . "_fwd_busy_state");
                    astdb("del", "Forwarding", "$ext" . "_fwd_busy_dst");
                    astdb("del", "Forwarding", "$ext" . "_fwd_no_answer_state");
                    astdb("del", "Forwarding", "$ext" . "_fwd_no_answer_time");
                    astdb("del", "Forwarding", "$ext" . "_fwd_no_answer_dst");
                }

                header("Location: ?action=show");
            }
        }

        if (empty($update_now)) {
            ?>
            <table align="center" border="0" width="90%" frame="void">
                <tr><td colspan="6" align="center"><B>Новое направление</B></td></tr></table>
            <form method="post" action="?action=add">
                <input type=hidden name=update_now value=post>
                <table align="center" cellspacing="2" cellpadding="2" width="450" frame="border" bgcolor="#f4f4f4">
                    <tr>
                        <td width="200"><B><? echo $Color0begin; ?>Внутренний номер:<? echo $Color0end; ?></B></td>
                        <td width="200"><input class="input" name="ext" value="<? echo $ext; ?>" /></td>
                    </tr>
                </table>
                <table align="center" border="0" width="90%" frame="void"><tr><td></td></tr></table>
                <table align="center" cellspacing="2" cellpadding="2" width="450" frame="border" bgcolor="#f4f4f4">
                    <tr>
                        <td width="200"><B>Безусловная</B>:</td>
                        <td width="200"><? print "<input type=\"checkbox\" $fwd_always_state_html name=\"fwd_always_state\" value=\"$fwd_always_state\">Включена"; ?></td>
                    </tr>
                    <tr>
                        <td width="200"><? echo $Color1begin; ?>Номер для переадресации:<? echo $Color1end; ?></td>
                        <td width="200"><input class="input" name="fwd_always_dst" value="<? echo $fwd_always_dst; ?>" /></td>
                    </tr>
                </table>
                <table align="center" border="0" width="90%" frame="void"><tr><td></td></tr></table>
                <table align="center" cellspacing="2" cellpadding="2" width="450" frame="border" bgcolor="#f4f4f4">
                    <tr>
                        <td width="200"><B>По-недоступности</B>:</td>
                        <td width="200"><? print "<input type=\"checkbox\" $fwd_not_available_state_html name=\"fwd_not_available_state\" value=\"$fwd_not_available_state\">Включена"; ?></td>
                    </tr>
                    <tr>
                        <td width="200"><? echo $Color2begin; ?>Номер для переадресации:<? echo $Color2end; ?></td>
                        <td width="200"><input class="input" name="fwd_not_available_dst" value="<? echo $fwd_not_available_dst; ?>" /></td>
                    </tr>
                </table>
                <table align="center" border="0" width="90%" frame="void"><tr><td></td></tr></table>
                <table align="center" cellspacing="2" cellpadding="2" width="450" frame="border" bgcolor="#f4f4f4">
                    <tr>
                        <td width="200"><B>По-занятости</B>:</td>
                        <td width="200"><? print "<input type=\"checkbox\" $fwd_busy_state_html name=\"fwd_busy_state\" value=\"$fwd_busy_state\">Включена"; ?></td>
                    </tr>
                    <tr>
                        <td width="200"><? echo $Color3begin; ?>Номер для переадресации<? echo $Color3end; ?></td>
                        <td width="200"><input class="input" name="fwd_busy_dst" value="<? echo $fwd_busy_dst; ?>" /></td>
                    </tr>
                </table>
                <table align="center" border="0" width="90%" frame="void"><tr><td></td></tr></table>
                <table align="center" cellspacing="2" cellpadding="2" width="450" frame="border" bgcolor="#f4f4f4">
                    <tr>
                        <td width="200"><B>По-неответу</B>:</td>
                        <td width="200"><? print "<input type=\"checkbox\" $fwd_no_answer_state_html name=\"fwd_no_answer_state\" value=\"$fwd_no_answer_state\">Включена"; ?></td>
                    </tr>
                    <tr>
                        <td width="200"><? echo $Color4begin; ?>Время (1-120 сек.):<? echo $Color4end; ?></td>
                        <td width="200"><input class="input" name="fwd_no_answer_time" value="<? echo $fwd_no_answer_time; ?>" /></td>
                    </tr>
                    <tr>
                        <td width="200"><? echo $Color5begin; ?>Номер для переадресации:<? echo $Color5end; ?></td>
                        <td width="200"><input class="input" name="fwd_no_answer_dst" value="<? echo $fwd_no_answer_dst; ?>" /></td>
                    </tr>
                </table>
                <table align="center" border="0" width="90%" frame="void"><tr><td></td></tr></table>
                <table align="center" border="0"  width="450" frame="void">
                    <tr align="center" valign="top">
                        <td><button type="submit">Сохранить</button></td>  
                        <td><button type="reset">Сбросить</button></form></td>
                        <td>
                            <form method="post" action="?action=show">
                                <input type="submit" value="Отменить">
                            </form>
                        </td>
                    </tr>
                </table>
                <?
            }
        } //Конец добавления нового направления

        if ($_GET['action'] == "del" AND $_GET['item'] != "") {
            $id = $_GET['item'];
            $id = $_GET['item'];
            if (!ctype_digit($id)) {
                header("Location: ?action=show");
            }

            $themes = mysql_fetch_array(mysql_query("SELECT * FROM forwarding WHERE id = '" . "$id" . "';"), 1);
            $ext = trim($themes['ext']);

            $query = "DELETE FROM forwarding WHERE id = '" . $id . "'";

            if (!mysql_query($query)) {
                echo "error!";
                exit();
            }
            astdb("del", "Forwarding", "$ext" . "_fwd_always_state");
            astdb("del", "Forwarding", "$ext" . "_fwd_always_dst");
            astdb("del", "Forwarding", "$ext" . "_fwd_not_available_state");
            astdb("del", "Forwarding", "$ext" . "_fwd_not_available_dst");
            astdb("del", "Forwarding", "$ext" . "_fwd_busy_state");
            astdb("del", "Forwarding", "$ext" . "_fwd_busy_dst");
            astdb("del", "Forwarding", "$ext" . "_fwd_no_answer_state");
            astdb("del", "Forwarding", "$ext" . "_fwd_no_answer_time");
            astdb("del", "Forwarding", "$ext" . "_fwd_no_answer_dst");
            header("Location: ?action=show");
        }

        function astdb($action, $var1, $var2, $var3, $var4, $var5) {


            $timeout = 15;

            $socket = fsockopen($asterisk_ip, $manager_port, $errno, $errstr, $timeout);
            if (!$socket) {
                echo 'Socket fail<br>';
                echo $errorno . '<br>';
                echo $errstr . '<br>';
                echo $timeout . '<br>';
            } else {

                if ($action == "del") {
# var1 = Family     var2 = Key
                    fputs($socket, "Action: Login\r\n");
                    fputs($socket, "UserName: $UserName\r\n");
                    fputs($socket, "Secret: $Secret\r\n\r\n");
                    fputs($socket, "Action: DBDel\r\n");
                    fputs($socket, "Family: $var1\r\n");
                    fputs($socket, "Key: $var2\r\n");
                    fputs($socket, "Action: Logoff\r\n\r\n");

 if (time_nanosleep(0, 100000000) === true) {
   # echo "Slept for 0.1 a second.\n";
}                   
                    
                    $wrets = fgets($socket, 128);
                }
                if ($action == "put") {
# var1 = Family     var2 = Key  var3 = Val
                    fputs($socket, "Action: Login\r\n");
                    fputs($socket, "UserName: $UserName\r\n");
                    fputs($socket, "Secret: $Secret\r\n\r\n");
                    fputs($socket, "Action: DBPut\r\n");
                    fputs($socket, "Family: $var1\r\n");
                    fputs($socket, "Key: $var2\r\n");
                    fputs($socket, "Val: $var3\r\n");
                    fputs($socket, "Action: Logoff\r\n\r\n");
                    
 if (time_nanosleep(0, 100000000) === true) {
   # echo "Slept for 0.1 a second.\n";
}     
                    
                    $wrets = fgets($socket, 128);
                }
            }
        }

################################################################################  
//}
