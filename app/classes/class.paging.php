<?php
/************************************************
*	========================================	*
*	Perfect MySQL Paging						*
*	========================================	*
*	Script Name: class.paging.php				*
*	Developed By: Khurram Adeeb Noorani			*
*	Email: khurramnoorani@gmail.com				*
*	My CV: http://www.visualcv.com/kanoorani	*
*	Twitter: http://www.twitter.com/kanoorani	*
*	Date Created: 08-JULY-2009					*
*	Last Modified: 08-JULY-2009					*
************************************************/
?>
<?php
class PAGING
{
	var $sql,$records,$pages;
	/*
	Variables that are passed via constructor parameters
	*/
	var $page_no,$total,$limit,$first,$previous,$next,$last,$start,$end,$jump;
	/*
	Variables that will be computed inside constructor
	*/
	function PAGING($sql,$records=16,$pages=5)
	{
		if($pages%2==0)
			$pages++;
		/*
		The pages should be odd not even
		*/
		$res=mysql_query($sql) or die($sql." - ".mysql_error());
		$total=mysql_num_rows($res);
		$page_no=isset($_GET["page_no"])?$_GET["page_no"]:1;
		/*
		Checking the current page
		If there is no current page then the default is 1
		*/
		$limit=($page_no-1)*$records;
		$sql.=" limit $limit,$records";
		/*
		The starting limit of the query
		*/
		$first=1;
		$previous=$page_no>1?$page_no-1:1;
		$next=$page_no+1;
		
		
		
		//$jump=$page_no+$pages;	
		$last=ceil($total/$records);
		
		if(($page_no + $pages) >= $last)
			$jump = $last;
		else
			$jump = $page_no + $pages;
		
		if($next>$last)
			$next=$last;
		if($next>$jump)
			$next=$jump;
		/*
		The first, previous, next and last page numbers have been calculated
		*/
		$start=$page_no;
		$end=$start+$pages-1;
		if($end>$last)
			$end=$last;
		/*
		The starting and ending page numbers for the paging
		*/
		if(($end-$start+1)<$pages)
		{
			$start-=$pages-($end-$start+1);
			if($start<1)
				$start=1;
		}
		if(($end-$start+1)==$pages)
		{
			$start=$page_no-floor($pages/2);
			$end=$page_no+floor($pages/2);
			while($start<$first)
			{
				$start++;
				$end++;
			}
			while($end>$last)
			{
				$start--;
				$end--;
			}
		}
		/*
		The above two IF statements are kinda optional
		These IF statements bring the current page in center
		*/
		$this->sql=$sql;
		$this->records=$records;
		$this->pages=$pages;
		$this->page_no=$page_no;
		$this->total=$total;
		$this->limit=$limit;
		$this->first=$first;
		$this->previous=$previous;
		$this->next=$next;
		$this->jump=$jump;
		$this->last=$last;
		$this->start=$start;
		$this->end=$end;
	}
	function show_paging($url,$params="")
	{ 
		$paging="";
		if($this->total>$this->records)
		{ 
			$page_no=$this->page_no;
			$first=$this->first;
			$previous=$this->previous;
			$next=$this->next;
			$jump=$this->jump;
			$last=$this->last;
			$start=$this->start;
			$end=$this->end;
			if($params=="")
				$params="?page_no=";
			else
				$params="?$params&page_no=";
			$paging.="<span class='paging'>";
			//$paging.="<span class='paging-current'>Page $page_no of $last</span>";
			//if($page_no==$first)
				//$paging.="<span class='paging-disabled'>&nbsp;&nbsp;<a href='javascript:void(0)'>&lt;&lt;</a>&nbsp;&nbsp;</span>";
			//else
				//$paging.="<span><a href='$url$params$first'>&lt;&lt;</a></span>";
			if($page_no==$previous)
				$paging.="<span  >&lt;<b>Previous</b>&nbsp;&nbsp;</span>";
			else
				$paging.="<span><a href='$url$params$previous'>&lt;<b>Previous</b></a>&nbsp;&nbsp;</span>";
			for($p=$start;$p<=$end;$p++)
			{
				$paging.="<span";
				if($page_no==$p)
					$paging.=" class='style14'";
				$paging.="><a href='$url$params$p'>$p</a>&nbsp;&nbsp;</span>";
			}
			if($page_no==$jump)
				$paging.="<span class='paging-current'>&gt;&gt;</span>";
			else
				$paging.="<span><a href='$url$params$jump'>&gt;&gt;</a>&nbsp;&nbsp;</span>";
			if($page_no==$last)
				$paging.="<span class='paging-current'>$page_no of $last&nbsp;&nbsp;</span>";
			else
				$paging.="<span>$page_no of&nbsp;&nbsp;<a href='$url$params$last'>$last</a>&nbsp;&nbsp;</span>";
			if($page_no==$next)
				$paging.="<span ><b>Next</b>&gt;&nbsp;&nbsp;</span>";
			else
				$paging.="<span><a href='$url$params$next'><b>Next</b>&gt;</a></span>";
			//if($page_no==$last)
				//$paging.="<span class='paging-disabled'><a href='javascript:void(0)'>&gt;&gt;</a>&nbsp;&nbsp;</span>";
			//else
				//$paging.="<span><a href='$url$params$last'>&gt;&gt;</a>&nbsp;&nbsp;</span>";
			$paging.="</span>";
			 
		} 
		return $paging;
	}
}
?>