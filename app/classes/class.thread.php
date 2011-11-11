<?php
class thread
{

    public $parents  = array();
    public $children = array();

    /**
     * @param array $comments
     */
    function __construct($comments)
    {
        foreach ($comments as $comment)
        {
            if ($comment['parent_id'] == '0')
            {
                $this->parents[$comment['id']][] = $comment;
            }
            else
            {
                $this->children[$comment['parent_id']][] = $comment;
            }
        }
		//print_r($this->parents);die();
    }

    /**
     * @param array $comment
     * @param int $depth
     */
    private function format_comment($comment, $depth)
    {
		
			
			
		echo '<br/><div style="padding-left:'.($depth*20).'px">';
		
		if($comment['by'])
			echo "<u>".$comment['by']."</u><br/>";
			
		if($comment['date'])
			echo " (<u><i>".$comment['date']."</i></u>)<br/>";
			
        /*for ($depth; $depth > 0; $depth--)
        {
            $tab = ($depth*10);
        }*/

        echo $comment['text'];
			
        echo "</div>\n";
    }

    /**
     * @param array $comment
     * @param int $depth
     */
    private function print_parent($comment, $depth = 0)
    {
        foreach ($comment as $c)
        {
            $this->format_comment($c, $depth);

            if (isset($this->children[$c['id']]))
            {
                $this->print_parent($this->children[$c['id']], $depth + 1);
            }
        }
    }

    public function print_comments()
    {
        foreach ($this->parents as $c)
        {
            $this->print_parent($c);
        }
    }

}


?>