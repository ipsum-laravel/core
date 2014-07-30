<?php namespace Ipsum\Core\Library;

class PaginationPresenter extends \Illuminate\Pagination\Presenter {

	/**
	 * Get HTML wrapper for a page link.
	 *
	 * @param  string  $url
	 * @param  int  $page
	 * @return string
	 */
	public function getPageLinkWrapper($url, $page)
	{
	    // TODO attention la pagination n'est pas forcément utilisé avec la Liste
	    // $nofollow = count($this->paginator->query) > 0 ? 'rel="nofollow"' : '';
	    $nofollow = '';

		return '<li><a '.$nofollow.' href="'.$url.'">'.$page.'</a></li>';
	}

	/**
	 * Get HTML wrapper for disabled text.
	 *
	 * @param  string  $text
	 * @return string
	 */
	public function getDisabledTextWrapper($text)
	{
		return '<li class="disabled"><span>'.$text.'</span></li>';
	}

	/**
	 * Get HTML wrapper for active text.
	 *
	 * @param  string  $text
	 * @return string
	 */
	public function getActivePageWrapper($text)
	{
		return '<li class="active"><span>'.$text.'</span></li>';
	}

}
