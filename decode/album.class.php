<?php
/*********************/
/*                   */
/*  Version : 5.1.0  */
/*  Author  : RM     */
/*  Comment : 071223 */
/*                   */
/*********************/

class album
{

    private $deptid = NULL;
    private $uesrpriv = NULL;
    private $userid = NULL;
    private $curdate = NULL;
    private $curdatetime = NULL;

    public function __construct( $userinfo = array( ) )
    {
        global $connection;
        if ( $userinfo['user_id'] == "" )
        {
            $this->userid = $_SESSION['LOGIN_USER_ID'];
        }
        else
        {
            $this->userid = $userinfo['user_id'];
        }
        if ( $this->userid )
        {
            $sql = "SELECT DEPT_ID,USER_PRIV FROM user WHERE USER_ID='".$this->userid."'";
            $rs = ( $connection, $sql );
            $row = ( $rs );
            $this->deptid = $row['DEPT_ID'];
            $this->uesrpriv = $row['USER_PRIV'];
        }
        $this->curdate = ( "Y-m-d", $row['USER_PRIV'] );
        $this->curdatetime = ( "Y-m-d H:i:s", ( "Y-m-d", $row['USER_PRIV'] ) );
    }

    public function getPhotoTab( )
    {
        global $connection;
        $LOGIN_USER_ID = $this->userid;
        $LOGIN_DEPT_ID = $this->deptid;
        $LOGIN_USER_PRIV = $this->uesrpriv;
        if ( $this->userid == "admin" )
        {
            $sql = "\r\n\t\t\t\tSELECT * FROM album_sort \r\n\t\t\t\t\tWHERE ALB_PARENT=0 \r\n\t\t\t\t\t\tORDER BY ALB_ORDER ASC \r\n\t\t\t\t";
        }
        else
        {
            $sql = "\r\n\t\t\t\tSELECT * FROM album_sort \r\n\t\t\t\t\tWHERE ALB_PARENT=0 \r\n\t\t\t\t\t\tAND ALB_ID IN (\r\n\t\t\t\t\t\t\tSELECT ALB_PARENT FROM album_sort \r\n\t\t\t\t\t\t\tWHERE ALB_CREATER = '{$LOGIN_USER_ID}'\r\n\t\t\t\t\t\t\tOR( DEPT_ID = 'ALL_DEPT'\r\n\t\t\t\t\t\t\t\tOR \r\n\t\t\t\t\t\t\t\t(INStr(DEPT_ID,',{$LOGIN_DEPT_ID},')>0 or INStr(DEPT_ID,'{$LOGIN_DEPT_ID},')=1) \r\n\t\t\t\t\t\t\t\tOR\r\n\t\t\t\t\t\t\t\t(INStr(PRIV_ID,',{$LOGIN_USER_PRIV},')>0 or INStr(PRIV_ID,'{$LOGIN_USER_PRIV},')=1)\r\n\t\t\t\t\t\t\t\tOR \r\n\t\t\t\t\t\t\t\t(INStr(USER_ID,',{$LOGIN_USER_ID},')>0 or INStr(USER_ID,'{$LOGIN_USER_ID},')=1)\r\n\t\t\t\t\t\t\t\tOR \r\n\t\t\t\t\t\t\t\t(INStr(MANAGE_USER,',{$LOGIN_USER_ID},')>0 or INStr(MANAGE_USER,'{$LOGIN_USER_ID},')=1)\r\n\t\t\t\t\t\t\t)\r\n\t\t\t\t\t\t\tGROUP BY ALB_PARENT\r\n\t\t\t\t\t\t)\r\n\t\t\t\t\t\tOR ALB_ID = 1\r\n\t\t\t\t\t\tORDER BY ALB_ORDER ASC \r\n\t\t\t\t";
        }
        $rs = ( $connection, $sql );
        while ( $row = ( $rs ) )
        {
            $list[] = $row;
        }
        return $list;
    }

}

include_once( "inc/conn.php" );
?>
