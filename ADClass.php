<?php
  /**
   * LDAP Connectin Class
   * 
   * 
   * @package    AD
   * @subpackage Controller
   * @author     Amauri da Silva Junior <amaurisjunior@gmail.com> Twitter: @amauripump
   */
namespace AD\Controller;

class ActiveDirectoryController
{

    var $USER;
    var $PASS;
    var $SERVER = ""; //Set your server here
    var $DOMAIN = ""; //Set your domain here
    var $BASE = ""; // Set your AD base query here
    var $STATUS;
    protected $results;

    var $connection;


    /**
     * Contructor method
     * @return: null
     */
    function __construct($user = "", $password = ""){
        $this->setUSER($user);
        $this->setPASS($password);

        $this->conect();

    }

    /**
     * Connect to LDAP Server
     * @return boolean: TRUE connected, FALSE disconnected
     */ 
    function conect(){

        /* Setting up debug level, if you dont want it, just comment line below */
        ldap_set_option(NULL, LDAP_OPT_DEBUG_LEVEL, 7);

        if (!($connect = ldap_connect($this->getSERVER()))){
            $this->setConnection(FALSE);
            $this->setSTATUS("DESCONECTADO");
            return FALSE;
        }else{
            $this->setConnection($connect);
            $this->setSTATUS("CONECTADO");
            return TRUE;
        }
         
    }

    /**
     * Auth with user and password to LDAP
     * @return boolean: TRUE autenticated, FALSE login failed
     */ 
    function authLDAP(){

        //Caso a classe não esteja conectada ao servidor
        if(!$this->getConnection()):
            return FALSE;
        endif;
        
        if($this->getUSER() == "" || $this->getPASS() == ""):
            return FALSE;
        endif;

        $userDominio = $this->getUSER() . $this->getDOMAIN();


        // Try to autenticate to server
        if (!($bind = ldap_bind($this->getConnection(), $userDominio, $this->getPASS()))):
            
            //echo "<hr>teste -> ".  ldap_error($this->getConnection()) . "<hr>";

            // se nao validar retorna false
            return FALSE;
        else:
            // se validar retorna true
            return $bind;
        endif;

    }

    function search(string $query){

            // binding to ldap server
            $ldapbind = $this->authLDAP();

            // verify binding
            if ($ldapbind) {
                $res = ldap_search($this->getConnection(), $this->getBASE(), $query);
                $this->setResults( (ldap_get_entries($this->getConnection(), $res)) );
                return TRUE;
            } else {
                return FALSE;
            }

    }

    /**
     * Get the value of USER
     */ 
    public function getUSER()
    {
        return $this->USER;
    }

    /**
     * Set the value of USER
     *
     * @return  self
     */ 
    public function setUSER($USER)
    {
        $this->USER = $USER;

        return $this;
    }

    /**
     * Get the value of PASSWORD
     */ 
    public function getPASS()
    {
        return $this->PASS;
    }

    /**
     * Set the value of PASSWORD
     *
     * @return  self
     */ 
    public function setPASS($PASS)
    {
        $this->PASS = $PASS;

        return $this;
    }

    /**
     * Get the value of SERVER
     */ 
    public function getSERVER()
    {
        return $this->SERVER;
    }

    /**
     * Set the value of SERVER
     *
     * @return  self
     */ 
    public function setSERVER($SERVER)
    {
        $this->SERVER = $SERVER;

        return $this;
    }

    /**
     * Get the value of DOMAIN
     */ 
    public function getDOMAIN()
    {
        return $this->DOMAIN;
    }

    /**
     * Set the value of DOMAIN
     *
     * @return  self
     */ 
    public function setDOMAIN($DOMAIN)
    {
        $this->DOMAIN = $DOMAIN;

        return $this;
    }

    /**
     * Get the value of connection
     */ 
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * Set the value of connection
     *
     * @return  self
     */ 
    public function setConnection($connection)
    {
        $this->connection = $connection;

        return $this;
    }

    /**
     * Get the value of STATUS
     */ 
    public function getSTATUS()
    {
        return $this->STATUS;
    }

    /**
     * Set the value of STATUS
     *
     * @return  self
     */ 
    public function setSTATUS($STATUS)
    {
        $this->STATUS = $STATUS;

        return $this;
    }

    /**
     * Get the value of BASE
     */ 
    public function getBASE()
    {
        return $this->BASE;
    }

    /**
     * Set the value of BASE
     *
     * @return  self
     */ 
    public function setBASE($BASE)
    {
        $this->BASE = $BASE;

        return $this;
    }

    /**
     * Get the value of results
     */ 
    public function getResults()
    {
        return $this->results;
    }

    /**
     * Set the value of results
     *
     * @return  self
     */ 
    public function setResults($results)
    {
        $this->results = $results;

        return $this;
    }
}
