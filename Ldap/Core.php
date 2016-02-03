<?php
namespace CarnegieLearning\LdapOrmBundle\Ldap;

/**
 * Class Core
 *
 * This is a wrapper class to make Unit Testing Easier. This is not meant to be unit tested, but rather mocked in unit tests
 * for classes that need to use ldap core functions. All functions reflect the core functions with underscored method names
 * converted to camelcase, as well as parameters. The only exception this is the method listSearch.
 *
 * @package CarnegieLearning\LdapOrmBundle\Ldap
 */
class Core
{
    /**
     * @link http://php.net/manual/en/function.ldap-add.php
     * @param $linkIdentifier
     * @param $dn
     * @param $entry
     * @return bool
     */
    public function add($linkIdentifier, $dn, $entry)
    {
        return ldap_add($linkIdentifier, $dn, $entry);
    }

    /**
     * @link http://php.net/manual/en/function.ldap-bind.php
     * @param $linkIdentifier
     * @param null $bindRdn
     * @param null $bindPassword
     * @return bool
     */
    public function bind($linkIdentifier, $bindRdn = null, $bindPassword = null)
    {
        return ldap_bind($linkIdentifier, $bindRdn, $bindPassword);
    }

    /**
     * @link http://php.net/manual/en/function.ldap-close.php
     * @param $linkIdentifier
     */
    public function close($linkIdentifier)
    {
        ldap_close($linkIdentifier);
    }

    /**
     * @link http://php.net/manual/en/function.ldap-compare.php
     * @param $linkIdentifier
     * @param $dn
     * @param $attribute
     * @param $value
     * @return mixed
     */
    public function compare($linkIdentifier, $dn, $attribute, $value)
    {
        return ldap_compare($linkIdentifier, $dn, $attribute, $value);
    }

    /**
     * @link http://php.net/manual/en/function.ldap-connect.php
     * @param null $hostname
     * @param int $port
     * @return resource || boolean
     */
    public function connect($hostname = null, $port = 389)
    {
        return ldap_connect($hostname, $port);
    }

    /**
     * @link http://php.net/manual/en/function.ldap-control-paged-result-response.php
     * @param $link
     * @param $result
     * @param null $cookie
     * @param null $estimated
     * @return bool
     */
    public function controlPagedResultResponse($link, $result, &$cookie = null, &$estimated = null)
    {
        return ldap_control_paged_result_response($link, $result, $cookie, $estimated);
    }

    /**
     * @link http://php.net/manual/en/function.ldap-control-paged-result.php
     * @param $link
     * @param $pagesize
     * @param bool $iscritical
     * @param string $cookie
     * @return bool
     */
    public function controlPagedResult($link, $pagesize, $iscritical = false, $cookie = "")
    {
        return ldap_control_paged_result($link, $pagesize, $iscritical, $cookie);
    }

    /**
     * @link http://php.net/manual/en/function.ldap-count-entries.php
     * @param $linkIdentifier
     * @param $resultIdentifier
     * @return int
     */
    public function countEntries($linkIdentifier, $resultIdentifier)
    {
        return ldap_count_entries($linkIdentifier, $resultIdentifier);
    }

    /**
     * @link http://php.net/manual/en/function.ldap-delete.php
     * @param $linkIdentifier
     * @param $dn
     * @return bool
     */
    public function delete($linkIdentifier, $dn)
    {
        return ldap_delete($linkIdentifier, $dn);
    }

    /**
     * @link http://php.net/manual/en/function.ldap-dn2fn.php
     * @param $dn
     * @return string
     */
    public function dn2fn($dn)
    {
        return ldap_dn2ufn($dn);
    }

    /**
     * @link http://php.net/manual/en/function.ldap-err2str.php
     * @param $errno
     * @return string
     */
    public function err2str($errno)
    {
        return ldap_err2str($errno);
    }

    /**
     * @param $linkIdentifier
     * @link http://php.net/manual/en/function.ldap-errno.php
     * @return int
     */
    public function errno($linkIdentifier)
    {
        return ldap_errno($linkIdentifier);
    }

    /**
     * @link http://php.net/manual/en/function.ldap-error.php
     * @param $linkIdentifier
     * @return string
     */
    public function error($linkIdentifier)
    {
        return ldap_error($linkIdentifier);
    }

    /**
     * @link http://php.net/manual/en/function.ldap-escape.php
     * @param $subject
     * @param null $ignore
     * @param null $escape
     * @return string
     */
    public function escape($subject, $ignore = null, $escape = null)
    {
        return ldap_escape($subject, $ignore, $escape);
    }

    /**
     * @link http://php.net/manual/en/function.ldap-explode-dn.php
     * @param $dn
     * @param $withAttribute
     * @return array
     */
    public function explodeDn($dn, $withAttribute)
    {
        return ldap_explode_dn($dn, $withAttribute);
    }

    /**
     * @link http://php.net/manual/en/function.ldap-first-attribute.php
     * @param $linkIdentifier
     * @param $resultEntryIdentifier
     * @return string
     */
    public function firstAttribute($linkIdentifier, $resultEntryIdentifier)
    {
        return ldap_first_attribute($linkIdentifier, $resultEntryIdentifier);
    }

    /**
     * @link http://php.net/manual/en/function.ldap-first-entry.php
     * @param $linkIdentifier
     * @param $resultIdentifier
     * @return resource
     */
    public function firstEntry($linkIdentifier, $resultIdentifier)
    {
        return ldap_first_entry($linkIdentifier, $resultIdentifier);
    }

    /**
     * @link http://php.net/manual/en/function.ldap-first-reference.php
     * @param $link
     * @param $result
     * @return resource
     */
    public function firstReference($link, $result)
    {
        return ldap_first_reference($link, $result);
    }

    /**
     * @link http://php.net/manual/en/function.ldap-free-result.php
     * @param $resultIdentifier
     * @return bool
     */
    public function freeResult($resultIdentifier)
    {
        return ldap_free_result($resultIdentifier);
    }

    /**
     * @link http://php.net/manual/en/function.ldap-get-attributes.php
     * @param $linkIdentifier
     * @param $resultEntryIdentifier
     * @return array
     */
    public function getAttributes($linkIdentifier, $resultEntryIdentifier)
    {
        return ldap_get_attributes($linkIdentifier, $resultEntryIdentifier);
    }

    /**
     * @link http://php.net/manual/en/function.ldap-get-dn.php
     * @param $linkIdentifier
     * @param $resultEntryIdentifier
     * @return string
     */
    public function getDn($linkIdentifier, $resultEntryIdentifier)
    {
        return ldap_get_dn($linkIdentifier, $resultEntryIdentifier);
    }

    /**
     * @link http://php.net/manual/en/function.ldap-get-entries.php
     * @param $linkIdentifier
     * @param $resultIdentifier
     * @return array
     */
    public function getEntries($linkIdentifier, $resultIdentifier)
    {
        return ldap_get_entries($linkIdentifier, $resultIdentifier);
    }

    /**
     * @link http://php.net/manual/en/function.ldap-get-option.php
     * @param $linkIdentifier
     * @param $option
     * @param $retval
     * @return bool
     */
    public function getOption($linkIdentifier, $option, &$retval)
    {
        return ldap_get_option($linkIdentifier, $option, $retval);
    }

    /**
     * @link http://php.net/manual/en/function.ldap-get-values-len.php
     * @param $linkIdentifier
     * @param $resultEntryIdentifier
     * @param $attribute
     * @return array
     */
    public function getValuesLen($linkIdentifier, $resultEntryIdentifier, $attribute)
    {
        return ldap_get_values_len($linkIdentifier, $resultEntryIdentifier, $attribute);
    }

    /**
     * @link http://php.net/manual/en/function.ldap-get-values.php
     * @param $linkIdentifier
     * @param $resultEntryIdentifier
     * @param $attribute
     * @return array
     */
    public function getValues($linkIdentifier, $resultEntryIdentifier, $attribute)
    {
        return ldap_get_values($linkIdentifier, $resultEntryIdentifier, $attribute);
    }

    /**
     * This is the only method that deviates from the core ldap as list is a reserved keyword for PHP
     *
     * @link http://php.net/manual/en/function.ldap-list.php
     * @param $linkIdentifier
     * @param $baseDn
     * @param $filter
     * @param array|null $attributes
     * @param null $attrsonly
     * @param null $sizelimit
     * @param null $timelimit
     * @param null $deref
     * @return resource
     */
    public function listSearch($linkIdentifier, $baseDn, $filter, array $attributes = null, $attrsonly = null, $sizelimit = null, $timelimit = null, $deref = null)
    {
        return ldap_list($linkIdentifier, $baseDn, $filter, $attributes, $attrsonly, $sizelimit, $timelimit, $deref);
    }

    /**
     * @link http://php.net/manual/en/function.ldap-mod-add.php
     * @param $linkIdentifier
     * @param $dn
     * @param $entry
     * @return bool
     */
    public function modAdd($linkIdentifier, $dn, $entry)
    {
        return ldap_mod_add($linkIdentifier, $dn, $entry);
    }

    /**
     * @link http://php.net/manual/en/function.ldap-mod-del.php
     * @param $linkIdentifier
     * @param $dn
     * @param $entry
     * @return bool
     */
    public function modDel($linkIdentifier, $dn, $entry)
    {
        return ldap_mod_del($linkIdentifier, $dn, $entry);
    }

    /**
     * @link http://php.net/manual/en/function.ldap-mod-replace.php
     * @param $linkIdentifier
     * @param $dn
     * @param $entry
     * @return bool
     */
    public function modReplace($linkIdentifier, $dn, $entry)
    {
        return ldap_mod_replace($linkIdentifier, $dn, $entry);
    }

    /**
     * @link http://php.net/manual/en/function.ldap-modify-batch.php
     * @param $linkIdentifier
     * @param $dn
     * @param $entry
     */
    public function modifyBatch($linkIdentifier, $dn, $entry)
    {
        ldap_modify_batch($linkIdentifier, $dn, $entry);
    }

    /**
     * @link http://php.net/manual/en/function.ldap-modify.php
     * @param $linkIdentifier
     * @param $dn
     * @param $entry
     * @return bool
     */
    public function modify($linkIdentifier, $dn, $entry)
    {
        return ldap_modify($linkIdentifier, $dn, $entry);
    }

    /**
     * @link http://php.net/manual/en/function.ldap-next-attribute.php
     * @param $linkIdentifier
     * @param $resultEntryIdentifier
     * @return string
     */
    public function nextAttribute($linkIdentifier, $resultEntryIdentifier)
    {
        return ldap_next_attribute($linkIdentifier, $resultEntryIdentifier);
    }

    /**
     * @link http://php.net/manual/en/function.ldap-next-entry.php
     * @param $linkIdentifier
     * @param $resultEntryIdentifier
     * @return resource
     */
    public function nextEntry($linkIdentifier, $resultEntryIdentifier)
    {
        return ldap_next_entry($linkIdentifier, $resultEntryIdentifier);
    }

    /**
     * @link http://php.net/manual/en/function.ldap-next-reference.php
     * @param $link
     * @param $entry
     * @return resource
     */
    public function nextReference($link, $entry)
    {
        return ldap_next_reference($link, $entry);
    }

    /**
     * @param $link
     * @link http://php.net/manual/en/function.ldap-parse-reference.php
     * @param $entry
     * @param $referrals
     * @return bool
     */
    public function parseReference($link, $entry, &$referrals)
    {
        return ldap_parse_reference($link, $entry, $referrals);
    }

    /**
     * @link http://php.net/manual/en/function.ldap-parse-result.php
     * @param $link
     * @param $result
     * @param $errcode
     * @param null $matcheddn
     * @param null $errmsg
     * @param array|null $referrals
     * @return bool
     */
    public function parseResult($link, $result, &$errcode, &$matcheddn = null, &$errmsg = null, array &$referrals = null)
    {
        return ldap_parse_result($link, $result, $errcode, $matcheddn, $errmsg, $referrals);
    }

    /**
     * @link http://php.net/manual/en/function.ldap-read.php
     * @param $linkIdentifier
     * @param $baseDn
     * @param $filter
     * @param array|null $attributes
     * @param null $attrsonly
     * @param null $sizelimit
     * @param null $timelimit
     * @param null $deref
     * @return resource
     */
    public function read($linkIdentifier, $baseDn, $filter, array $attributes = null, $attrsonly = null, $sizelimit = null, $timelimit = null, $deref = null)
    {
        return ldap_read($linkIdentifier, $baseDn, $filter, $attributes, $attrsonly, $sizelimit, $timelimit, $deref);
    }

    /**
     * @link http://php.net/manual/en/function.ldap-rename.php
     * @param $linkIdentifier
     * @param $dn
     * @param $newrdn
     * @param $newparent
     * @param $deleteoldrdn
     * @return bool
     */
    public function rename($linkIdentifier, $dn, $newrdn, $newparent, $deleteoldrdn)
    {
        return ldap_rename($linkIdentifier, $dn, $newrdn, $newparent, $deleteoldrdn);
    }

    /**
     * @link http://php.net/manual/en/function.ldap-sasl-bind.php
     * @param $link
     * @param null $binddn
     * @param null $password
     * @param null $saslMech
     * @param null $saslRealm
     * @param null $saslAuthcId
     * @param null $saslAuthzId
     * @param null $props
     * @return bool
     */
    public function saslBind($link, $binddn = null, $password = null, $saslMech = null, $saslRealm = null, $saslAuthcId = null, $saslAuthzId = null, $props = null)
    {
        return ldap_sasl_bind($link, $binddn, $password, $saslMech, $saslRealm, $saslAuthcId, $saslAuthzId, $props);
    }

    /**
     * @link http://php.net/manual/en/function.ldap-search.php
     * @param $linkIdentifier
     * @param $baseDn
     * @param $filter
     * @param array|null $attributes
     * @param null $attrsonly
     * @param null $sizelimit
     * @param null $timelimit
     * @param null $deref
     * @return resource
     */
    public function search($linkIdentifier, $baseDn, $filter, array $attributes = null, $attrsonly = null, $sizelimit = null, $timelimit = null, $deref = null)
    {
        return ldap_search($linkIdentifier, $baseDn, $filter, $attributes, $attrsonly, $sizelimit, $timelimit, $deref);
    }

    /**
     * @link http://php.net/manual/en/function.ldap-set-option.php
     * @param $linkIdentifier
     * @param $option
     * @param $newval
     * @return bool
     */
    public function setOption($linkIdentifier, $option, $newval)
    {
        return ldap_set_option($linkIdentifier, $option, $newval);
    }

    /**
     * @link http://php.net/manual/en/function.ldap-set-rebind-proc.php
     * @param $link
     * @param $callback
     * @return bool
     */
    public function setRebindProc($link, $callback)
    {
        return ldap_set_rebind_proc($link, $callback);
    }

    /**
     * @link http://php.net/manual/en/function.ldap-sort.php
     * @param $link
     * @param $result
     * @param $sortfilter
     * @return bool
     */
    public function sort($link, $result, $sortfilter)
    {
        return ldap_sort($link, $result, $sortfilter);
    }

    /**
     * @link http://php.net/manual/en/function.ldap-start-tls.php
     * @param $link
     * @return bool
     */
    public function startTls($link)
    {
        return ldap_start_tls($link);
    }

    /**
     * @link http://php.net/manual/en/function.ldap-unbind.php
     * @param $linkIdentifier
     * @return bool
     */
    public function unbind($linkIdentifier)
    {
        return ldap_unbind($linkIdentifier);
    }
}