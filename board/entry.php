<?php

    include("./inc/inc.page_top.php");

    ////////////////////////////////////// SORTIERFUNKTION BEGIN ////////////
    include("./inc/func.sort.php");
    ////////////////////////////////////// SORTIERFUNKTION ENDE ////////////


    function GetTime($time)
    {


        global $weekdays, $DateOrder, $cfg_p_mesi2, $cfg_p_mesi3, $cfg_p_mesi4;

        $time = explode(' ',$time);

        if($DateOrder!='en')
        {

	        $date = explode('/',$time[0]);

            $time[0] = $date[1].'.'.$date[0].'.'.$date[2];

        }
        else
        {

	        $time[0] = preg_replace('/\//','.',$time[0]);

        }


        $date = $weekdays[$time[2]]."$cfg_p_mesi3 $time[0] $cfg_p_mesi4 $time[1]" ;

        return $date;

    }



    function getftime($time)
    {

        global $weekdays, $datesub, $DateOrder;

        $time = explode(' ',$time);

        if($DateOrder!='en')
        {

		    $date = explode('/',$time[0]);

            $time[0] = $date[1].'/'.$date[0].'/'.$date[2];

        }

        $date = sprintf
        (

            "%08s %02s %08s",
            $time[1],

            substr
            (

                $weekdays[$time[2]],

                0,

                $datesub

            ),

            $time[0]);

            return $date;

    }



    $data = "./".$cfg_MsgDir."/" . $show . ".txt";

    $follow_up = "<b>$cfg_p_followups</b><br><br>";


    if (file_exists($data))
    {

	    $file = fopen("$data","r");

        $name = chop(fgets($file, 1000));

        $email = chop(fgets($file, 1000));

        $subject = chop(fgets($file, 1000));

        $time = chop(fgets($file, 1000));
	

        $time = GetTime($time);
	
	    $url = chop(fgets($file, 1000));

        $url_title = chop(fgets($file, 1000));


        while ($data = nl2br(fgets($file, 1000)))
        {

		    $message .= $data;
	    }

	    fclose($file);

        $dem = $show.".";


        while (list ($key, $val) = each ($all))
        {

		    $msg_num = substr ($val, 0,strlen($dem));

            if ( (strlen($val) > strlen($dem)) and ($msg_num == $dem))
            {

                // wenn L�nge gr��er und der EIntrag mit den Selben Nummern beginnt = Follow_up

                $sub = explode (".", $val);

                $sab = explode (".", $dem);

                $len = (count($sub)-count($sab))*6;

                $pusher = "";

                for ($i = 1; $i <= $len; $i++)
                {

				    $pusher = $pusher . "&nbsp;";

                }


			    $entry = "./".$cfg_MsgDir."/" . $val . ".txt";

                if (file_exists($entry))
                {

			        $ffile = fopen("$entry","r");

                    $fname = chop(fgets($ffile, 1000));

                    $femail = chop(fgets($ffile, 1000));

                    $fsubject = chop(fgets($ffile, 1000));

                    $ftime = chop(fgets($ffile, 1000));
			
			        $ftime = getftime($ftime);

                    fclose($ffile);

                    $fEntry .= "$pusher<font face=\"$cfg_type\"><b>&#8226; &nbsp;<a href=\"entry.php?show=$val\" class=forum>$fsubject</a></b> - ";


                    if (preg_match("/[a-zA-Z0-9]/",$femail))
                    {

			            $fEntry .= "<a href=\"mailto:$femail\" >";

                        $tag = "a";

                    }
                    else
                    {

			            $fEntry .= "<b>";

                        $tag = "b";

                    }

			        $fEntry .= $fname;

                    $fEntry .= "</$tag> - <i>$ftime</i></font><br>";

                } // END Entry exists

            } // END Strlen Vergleich

        } // END While

        if ($fEntry == "")
        {

		    $follow_up = "<b><a name=\"followups\">$cfg_p_no_followups</a></b>";

        }
        else
        {

		    $follow_up .= $fEntry;

        }


	    if (strstr ($show, ".") != "")
        {

		    $answer = explode(".",$show);

            array_splice($answer,-1);

            $answer = implode(".",$answer);

            $entry = "./".$cfg_MsgDir."/" . $answer . ".txt";

            if (file_exists($entry))
            {

		        $ffile = fopen("$entry","r");

                $fname = chop(fgets($ffile, 1000));

                $femail = chop(fgets($ffile, 1000));

                $fsubject = chop(fgets($ffile, 1000));

                fclose($ffile);

                $answer = "<br><br>$cfg_p_mesin1 &nbsp;<a href=\"entry.php?show=$answer\" >$fsubject</a> $cfg_p_mesin2 $fname<br><br><br>";

            }


	    }

        include("./inc/inc.entry.php");

    }
    else
    {

	    $error_msg01 = "File not found";

        $error_msg02 = "The required file was <b>not</b> found";

        include("./inc/inc.error.php");

    } // END Data-File Exists

    include("./inc/inc.page_bot.php");

?>