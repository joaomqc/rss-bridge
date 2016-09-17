<?php
class NiceMatinBridge extends FeedExpander {

	const MAINTAINER = "pit-fgfjiudghdf";
	const NAME = "NiceMatin";
	const URI = "http://www.nicematin.com/";
	const DESCRIPTION = "Returns the 10 newest posts from NiceMatin (full text)";

	public function collectData(){
		$this->collectExpandableDatas(self::URI . 'derniere-minute/rss', 10);
	}

	protected function parseItem($newsItem){
		$item = parent::parseItem($newsItem);
		$item['content'] = $this->NiceMatinExtractContent($item['uri']);
		return $item;
	}

	private function NiceMatinExtractContent($url) {
		$html = $this->getSimpleHTMLDOMCached($url);
		if(!$html)
			return 'Could not acquire content from url: ' . $url . '!';

		$content = $html->find('article', 0);
		if(!$content)
			return 'Could not find \'section\'!';

		$text = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $content->innertext);
		$text = strip_tags($text, '<p><a><img>');
		return $text;
	}
}
