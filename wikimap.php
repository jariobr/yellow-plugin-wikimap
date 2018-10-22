<?php
// Wikimap plugin, https://github.com/datenstrom/yellow-plugins/tree/master/wikimap
// Copyright (c) 2013-2017 Datenstrom, https://datenstrom.se
// This file may be used and distributed under the terms of the public license.

class YellowWikimap
{
	const VERSION = "0.7.6";
	var $yellow;			//access to API
	
	// Handle initialisation
	function onLoad($yellow)
	{
		$this->yellow = $yellow;
		$this->yellow->config->setDefault("wikimapPaginationLimit", "1000");
		$this->yellow->config->setDefault("wikimapLocation", "/wikimap/");
		$this->yellow->config->setDefault("wikimapFileXml", "wikimap.xml");
		$this->yellow->config->setDefault("wikimapFilter", "wiki");
	}

	// Handle page parsing
	function onParsePage()
	{
		if($this->yellow->page->get("template")=="wikimap")
		{
			$wikimapFilter = $this->yellow->config->get("wikimapFilter");
			$chronologicalOrder = ($this->yellow->config->get("wikimapFilter")!="wiki");
			$pagination = $this->yellow->config->get("contentPagination");
			if($_REQUEST[$pagination]==$this->yellow->config->get("wikimapFileXml"))
			{
				$pages = $this->yellow->pages->index(false, false);
				if(!empty($wikimapFilter)) $pages->filter("template", $wikimapFilter);
				$pages->sort($chronologicalOrder ? "modified" : "published", false);
				$pages->limit($this->yellow->config->get("wikimapPaginationLimit"));
				$this->yellow->page->setLastModified($pages->getModified());
				$this->yellow->page->setHeader("Content-Type", "text/xml; charset=utf-8");
				$output = "<?xml version=\"1.0\" encoding=\"utf-8\"\077>\r\n";
				$output .= "<urlset	xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"	xsi:schemaLocation=\"http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd\" xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\r\n";
				//$output .= "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\r\n";
				foreach($pages as $page)
				{
					$timestamp = strtotime($page->get($chronologicalOrder ? "modified" : "published"));
					$output .= "<url><loc>".$page->getUrl()."</loc></url>\r\n";			
				}
				$output .= "</urlset>\r\n";
				$this->yellow->page->setOutput($output);
			} else {
				$pages = $this->yellow->pages->index(false, false);
				if(!empty($wikimapFilter)) $pages->filter("template", $wikimapFilter);
				$pages->sort($chronologicalOrder ? "modified" : "published");
				$pages->pagination($this->yellow->config->get("wikimapPaginationLimit"));
				if(!$pages->getPaginationNumber()) $this->yellow->page->error(404);
				$this->yellow->page->set("wikimapChronologicalOrder", $chronologicalOrder);
				$this->yellow->page->setPages($pages);
				$this->yellow->page->setLastModified($pages->getModified());
			}
		}
	}
	
	// Handle page extra HTML data
	function onExtra($name)
	{
		$output = NULL;
		if($name=="header")
		{
			$pagination = $this->yellow->config->get("contentPagination");			
			$locationWikimap = $this->yellow->config->get("serverBase").$this->yellow->config->get("wikimapLocation");
			$locationWikimap .= $this->yellow->toolbox->normaliseArgs("$pagination:".$this->yellow->config->get("wikimapFileXml"), false);
			$output = "<link rel=\"sitemap\" type=\"text/xml\" href=\"$locationWikimap\" />\n";
		}
		return $output;
	}
}

$yellow->plugins->register("wikimap", "YellowWikimap", YellowWikimap::VERSION);
?>