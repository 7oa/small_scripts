<?
function _print_r($str, $die=false)
{
	if ($GLOBALS['USER']->isAdmin())
	{
		if(is_array($str))
		{
			echo '<pre style="font-size:11px; margin:0 0 15px 0; padding:5px; color:#000000 !important; background-color:#ededed; text-align:left !important;">'; print_r($str); echo '</pre>';
		}
		else
			echo $str;

		if($die)
		{
			echo $die;
			die();
		}
	}
}

//проверка на бота в форме Онлайн заявка
AddEventHandler("iblock", "OnBeforeIBlockElementAdd", Array("AntiSpam", "OnBeforeIBlockElementAddHandler"));

//выдаем ошибку если в тексте письма нет русских букв или есть href
class AntiSpam
{
    // создаем обработчик события "OnBeforeIBlockElementAdd"
    function OnBeforeIBlockElementAddHandler(&$arFields)
    {
		$detail_text=$arFields["DETAIL_TEXT"];
		if(($arFields["IBLOCK_ID"]==7)&&((!preg_match("/[а-яё]/iu",$detail_text))||(stristr($detail_text, 'href'))))
			{
				global $APPLICATION;
				$APPLICATION->throwException("В тексте сообщения нет русских букв. Подозрение на спам.");
				
				return false;
			}
    }
}
?>