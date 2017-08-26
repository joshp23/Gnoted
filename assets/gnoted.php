<?php

$notes = getNotes();
function getNotes() {
	global $APP_PATH;

	$notes = array();
	$files = scandir($APP_PATH);
	foreach($files as $file) {
		$path_parts = pathinfo($file);
		$ext = pathinfo($file, PATHINFO_EXTENSION);
		if($file != "." && $file != ".." && $ext=="note") {
			$note = array();
			$note["id"] = $path_parts["filename"];
			$note["rev"] = "0";
			$note["content"] = getNote($note["id"], $note["rev"], false);
			$notes[$note["id"]] = $note;
		}
	}

	uasort($notes, 'compareTitle');
	return $notes;
}

function getNote($id, $rev, $full) {
	global $APP_PATH, $notes;
	$ret = array();
	$path = $APP_PATH . "/" . $id . ".note";
	$xmlnotedoc = new DOMDocument();
	$xmlnotedoc->resolveExternals = false;        
	$xmlnotedoc->load($path);
	$node = $xmlnotedoc->documentElement;
		foreach($node->childNodes as $cn) {
			$ret["tag"] = "Unfiled";
			switch($cn->nodeName) {
				case "title":
					$ret["title"] = utf8_decode($cn->nodeValue); 
					break;
				case "tags":
					$tag = utf8_decode($cn->nodeValue);
					if (strpos($tag, 'template') !== false) {
						$tag = "template";
					} else {
						$tag = str_replace("system:notebook:", null, $tag);
					}
					$ret["tag"] = $tag;
					break;
				case "text":
					if($full) {
						$note_content_node = $cn->childNodes->item(0);
						$ret["text"] = utf8_decode($xmlnotedoc->saveXML($note_content_node));
						$ret["text"] = str_replace("\n", "<br/>\n", $ret["text"]);
						$ret["text"] = str_replace("<note-content version=\"0.1\">" . $ret["title"] . "<br/>", "<h1>". $ret["title"] . "</h1>", $ret["text"]);
						$ret["text"] = str_replace("</note-content>", "", $ret["text"]);
						/* bold text */
						$ret["text"] = str_replace("<bold>", "<b>", $ret["text"]);
						$ret["text"] = str_replace("</bold>", "</b>", $ret["text"]);
						/* lists */
						$ret["text"] = str_replace("<list>", "<ul>", $ret["text"]);
						$ret["text"] = str_replace("</list>", "</ul>", $ret["text"]);
						/* listitems */
						$ret["text"] = str_replace("<list-item", "<li", $ret["text"]);
						$ret["text"] = str_replace("</list-item>", "</li>", $ret["text"]);
						// clean url
						$ret["text"] = str_replace("<link:url>", " ", $ret["text"]);
						$ret["text"] = str_replace("</link:url>", " ", $ret["text"]);
						// datetime
						$ret["text"] = str_replace("<datetime>", "<small><i>", $ret["text"]);
						$ret["text"] = str_replace("</datetime>", "</i></small>", $ret["text"]);
						// highlight
						$ret["text"] = str_replace("<highlight>", "<span style='background:yellow;'>", $ret["text"]);
						$ret["text"] = str_replace("</highlight>", "</span>", $ret["text"]);
						// italic
						$ret["text"] = str_replace("<italic>", "<i>", $ret["text"]);
						$ret["text"] = str_replace("</italic>", "</i>", $ret["text"]);
						// underline
						$ret["text"] = str_replace("<underline>", "<u>", $ret["text"]);
						$ret["text"] = str_replace("</underline>", "</u>", $ret["text"]);
						// strikethrough
						$ret["text"] = str_replace("<strikethrough>", "<s>", $ret["text"]);
						$ret["text"] = str_replace("</strikethrough>", "</s>", $ret["text"]);
						// monospace
						$ret["text"] = str_replace("<monospace>", "<span style='font-family:monospace;'>", $ret["text"]);
						$ret["text"] = str_replace("</monospace>", "</span>", $ret["text"]);
						/* links */
						$ret["text"] = resolveInternalLinks($notes, $ret["text"]);

					}
			} 
		}

	return $ret;
}

function resolveInternalLinks($notes, $text) {
	$links = array();
	foreach($notes as $note) {
		$link = array();
		$link["string"] = "<link:internal>" . $note["content"]["title"] . "</link:internal>";
		$link["title"] = $note["content"]["title"];
		$link["id"] = $note["id"];
		$links[] = $link;
	}
	foreach($links as $link) {
		$text = str_replace($link["string"], "<a href=\"?note=" . $link["id"] . "\">" . $link["title"] . "</a>&crarr;", $text);
	}
	return $text;
}

function compareTitle($a, $b) {
		return strnatcmp($a["content"]["title"], $b["content"]["title"]);
}

$tags  = getTags($notes);
function getTags($notes) {
	$result = array();
	foreach($notes as $note) {
		if($note["content"]["tag"] !== "template" && (!in_array($note["content"]["tag"], $result))) { 
		$result[] = $note["content"]["tag"]; 
		}
	}
	sort($result);
	return $result;
}
?>
