<?php
global $httprotocol;
global $simple_url;
$full_simple_url = $httprotocol.$simple_url;
$fullmsg = "<style>body {background: #fbfbfb;}</style>";
$fullmsg .= '<table style="margin: 30px auto auto auto; width: 100%; max-width: 1000px; background: #fff; border: 1px solid #e5e5e5; border-top: 6px solid #27293e;" cellpadding="0" cellspacing="0">';
	$fullmsg .= '<tr>';
		$fullmsg .= '<td style="padding: 40px 40px 40px 40px;">';
			$fullmsg .= '<table style="width: 100%;" cellpadding="0" cellspacing="0">';
				$fullmsg .= '<tr>';
					$fullmsg .= '<td style="font-family: verdana; padding: 0 20px 18px 20px; color: #fff; font-size: 40px; text-align: center;">';
						$fullmsg .= '<img style="display: block; margin: auto auto 10px auto; max-width: 300px;" src="'.$full_simple_url.'/_core/_cdn/img/logo.png"/>';
					$fullmsg .= '</td>';
				$fullmsg .= '</tr>';
				
				$fullmsg .= '<tr>
								<td style="font-family: verdana; padding: 20px 20px 16px 20px;background: #27293e; color: #fff; font-size: 24px; text-align: center;">
									'.$title.'
								</td>
							</tr>';
				$fullmsg .= '<tr>';
					$fullmsg .= '<td style="font-family: verdana; padding: 20px 20px 16px 20px;background: #fff; color: #363636; font-size: 20px; line-height: 26px; text-align: center;">';
						$fullmsg .= $msg;
					$fullmsg .= '</td>';
				$fullmsg .= '</tr>';

				$fullmsg .= '<tr><td><div style="height:0;"></div></td></tr>';

			$fullmsg .= '</table>';
		$fullmsg .= '</td>';
	$fullmsg .= '</tr>';
$fullmsg .= '</div>';

// return $fullmsg;