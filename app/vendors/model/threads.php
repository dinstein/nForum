<?php
/****************************************************
 * FileName: app/vendors/model/threads.php
 * Author: xw <wei.xiao.bupt@gmail.com>
 *****************************************************/
App::import("vendor", array("model/article", "inc/pagination"));

/**
 * class Threads is a Article but contains other article which groupid equal its id
 * other than the properties in Article, Threads has three new properties
 * $this->FIRST is the first Article of Threads
 * $this->LAST is the last Article of Threads
 * $this->articleNum is number of articles in Threads
 * 
 * if the first article is deleted, it will be the next article,
 * but its ID  will be not threads id, and its GROUPID also will be wrong 
 * when get from function getInstance(what a fuck)
 * using the last article's GROUPID to find threads ID
 *
 * @extends Article
 * @implements Pageable       
 * @author xw
 */
class Threads extends Article implements Pageable{

    /**
     * number of articles 
     * @var int $articleNum
     */
    public $articleNum;

    /**
     * the reference of last article
     * @var Article $_last
     */
    private $_last;

    /**
     * array of all the articles
     * @var array $_articles
     */
    private $_articles = array();

    /**
     * function getInstance get a Threads object via $gid & $board
     * suggest using this method to get a ref of Threads
     *
     * @param int $gid
     * @param Board $board
     * @return Threads object
     * @static
     * @access public
     * @throws ThreadsNullException
     */
    public static function getInstance($gid, $board){
        $arr = array();
        $haveprev = null;
        $gid = intval($gid);
        $ret = bbs_get_threads_from_gid($board->BID, $gid, $gid, $arr, $haveprev);
        if($ret == 0 || count($arr) == 0)
            throw new ThreadsNullException();
        $num = count($arr);
        return new Threads(array($arr[0], $arr[$num - 1], $num), $board, $arr);
    }

    /**
     * function search
     *
     * @param Board $board
     * @param string $t1
     * @param string $t2
     * @param string $tn not contain
     * @param string $author
     * @param int $day
     * @param boolean $m
     * @param boolean $a attachment
     * @param int $return result number
     * @return array
     * @access public
     */
    public static function search($board, $t1, $t2, $tn, $author, $day, $m, $a, $return){
        $m = ($m)?1:0;
        $a = ($a)?1:0;
        $ret = bbs_searchtitle($board->NAME, $t1, $t2, $tn, $author, intval($day), $m, $a, intval($return));
        if(!is_array($ret))
            return array();
        foreach($ret as &$v){
            $v = new Threads($v, $board);
        }
        return $ret;
    }

    /**
     * function __contstruct()
     * The structure of $info is
     * array(2) {
     *         [0|origin]=> first Article 
     *         [1|lastreply]=> last Article 
     *         [2|articlenum]=> num
     * }
     * do not use this to get a object
     *
     * @param array $info
     * @param Board $board
     * @param array $articles
     * @return Threads
     * @access public
     * @throws ThreadsNullException
     */
    public function __construct($info, $board, $articles = null) {
        if(!array($info) || count($info) != 3)
            throw new ThreadsNullException();
        if(!is_a($board, "Board"))
            throw new ThreadsNullException();

        //make sure it is number-index
        $info = array_values($info);
        $this->articleNum = intval($info[2]);
        $this->_board = $board;
        $this->_last = new Article($info[1], $this->_board, $this->articleNum - 1);
        try{
            parent::__construct($info[0], $this->_board, 0);
        }catch(ArticleNullException $e){
            throw new ThreadsNullException();
        }
        if(is_array($articles)){
            foreach($articles as $k=>$v){
                try{
                    $this->_articles[] = new Article($v, $this->_board, $k);    
                }catch(Exception $e){
                    throw new ThreadsNullException();
                }
            }
        }
    }

    public function __get($name){
        switch($name){
            case "FIRST":
                return $this;
            case "LAST":
                return $this->_last;
            default:
                return parent::__get($name);
        }
    }

    public function getTotalNum(){
        return $this->articleNum;
    }

    public function getRecord($start, $num){
        return array_slice($this->_articles, $start - 1, $num);
    }

    public function getArticle($pos) {
        if(isset($this->_articles[$pos]))
            return $this->_articles[$pos];
        return null;
    }

    /**
     * function delete remove the threads
     *
     * @access public
     * @override
     */
    public function delete(){
        $this->deleteArticles(0, $this->articleNum);
    }
    /**
     * function deletedArticles delete the articles in threads
     * no exception 
     *
     * @param int $pos
     * @param int $num
     * @access public
     */
    public function deleteArticles($pos, $num){
        $i = 0;
        while($i <= $num){
            $a = $this->getArticle($pos + $i);
            if(!is_null($a))
                $a->delete();
            $i ++;
        }
    }
}
class ThreadsNullException extends Exception {}
?>
