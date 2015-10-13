<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
namespace Aura\Filter\Rule\Validate;

use Aura\Filter\Exception;

/**
 *
 * Check that an email address conforms to RFCs 5321, 5322 and others.
 *
 * As of Version 3.0, we are now distinguishing clearly between a Mailbox
 * as defined by RFC 5321 and an addr-spec as defined by RFC 5322. Depending
 * on the context, either can be regarded as a valid email address. The
 * RFC 5321 Mailbox specification is more restrictive (comments, white space
 * and obsolete forms are not allowed).
 *
 * Read the following RFCs to understand the constraints:
 *
 * - <http://tools.ietf.org/html/rfc5321>
 * - <http://tools.ietf.org/html/rfc5322>
 * - <http://tools.ietf.org/html/rfc4291#section-2.2>
 * - <http://tools.ietf.org/html/rfc1123#section-2.1>
 * - <http://tools.ietf.org/html/rfc3696> (guidance only)
 *
 * Copyright © 2008-2011, Dominic Sayers
 * Test schema documentation Copyright © 2011, Daniel Marschall
 * All rights reserved.
 *
 * ---
 *
 * N.B.: Refactored by Paul M. Jones, from Dominic Sayers' is_email() function
 * to methods and properties. Errors and omissions should be presumed to be a
 * result of the refactoring, not of the original function.
 *
 * Further, this validation rule converts IDNs to ASCII, which is not required
 * per se by any of the email RFCs.
 *
 * ---
 *
 * @package Aura.Filter
 *
 * @author Dominic Sayers <dominic@sayers.cc>
 *
 * @author Paul M. Jones <pmjones88@gmail.com>
 *
 * @copyright 2008-2011 Dominic Sayers
 *
 * @copyright 2015 Paul M. Jones
 *
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 *
 * @link http://www.dominicsayers.com/isemail
 *
 * @version 3.04.1 - Changed my link to http://isemail.info throughout
 *
 */
class Email
{
    /*:diagnostic constants start:*/

    // Categories
    const VALID_CATEGORY = 1;
    const DNSWARN = 7;
    const RFC5321 = 15;
    const CFWS = 31;
    const DEPREC = 63;
    const RFC5322 = 127;
    const ERR = 255;

    // Diagnoses
    // Address is valid
    const VALID = 0;
    // Address is valid but a DNS check was not successful
    const DNSWARN_NO_MX_RECORD = 5;
    const DNSWARN_NO_RECORD = 6;
    // Address is valid for SMTP but has unusual elements
    const RFC5321_TLD = 9;
    const RFC5321_TLDNUMERIC = 10;
    const RFC5321_QUOTEDSTRING = 11;
    const RFC5321_ADDRESSLITERAL = 12;
    const RFC5321_IPV6DEPRECATED = 13;
    // Address is valid within the message but cannot be used unmodified for the envelope
    const CFWS_COMMENT = 17;
    const CFWS_FWS = 18;
    // Address contains deprecated elements but may still be valid in restricted contexts
    const DEPREC_LOCALPART = 33;
    const DEPREC_FWS = 34;
    const DEPREC_QTEXT = 35;
    const DEPREC_QP = 36;
    const DEPREC_COMMENT = 37;
    const DEPREC_CTEXT = 38;
    const DEPREC_CFWS_NEAR_AT = 49;
    // The address is only valid according to the broad definition of RFC 5322.
    // It is otherwise invalid.
    const RFC5322_DOMAIN = 65;
    const RFC5322_TOOLONG = 66;
    const RFC5322_LOCAL_TOOLONG = 67;
    const RFC5322_DOMAIN_TOOLONG = 68;
    const RFC5322_LABEL_TOOLONG = 69;
    const RFC5322_DOMAINLITERAL = 70;
    const RFC5322_DOMLIT_OBSDTEXT = 71;
    const RFC5322_IPV6_GRPCOUNT = 72;
    const RFC5322_IPV6_2X2XCOLON = 73;
    const RFC5322_IPV6_BADCHAR = 74;
    const RFC5322_IPV6_MAXGRPS = 75;
    const RFC5322_IPV6_COLONSTRT = 76;
    const RFC5322_IPV6_COLONEND = 77;
    // Address is invalid for any purpose
    const ERR_EXPECTING_DTEXT = 129;
    const ERR_NOLOCALPART = 130;
    const ERR_NODOMAIN = 131;
    const ERR_CONSECUTIVEDOTS = 132;
    const ERR_ATEXT_AFTER_CFWS = 133;
    const ERR_ATEXT_AFTER_QS = 134;
    const ERR_ATEXT_AFTER_DOMLIT = 135;
    const ERR_EXPECTING_QPAIR = 136;
    const ERR_EXPECTING_ATEXT = 137;
    const ERR_EXPECTING_QTEXT = 138;
    const ERR_EXPECTING_CTEXT = 139;
    const ERR_BACKSLASHEND = 140;
    const ERR_DOT_START = 141;
    const ERR_DOT_END = 142;
    const ERR_DOMAINHYPHENSTART = 143;
    const ERR_DOMAINHYPHENEND = 144;
    const ERR_UNCLOSEDQUOTEDSTR = 145;
    const ERR_UNCLOSEDCOMMENT = 146;
    const ERR_UNCLOSEDDOMLIT = 147;
    const ERR_FWS_CRLF_X2 = 148;
    const ERR_FWS_CRLF_END = 149;
    const ERR_CR_NO_LF = 150;
    /*:diagnostic constants end:*/

    // function control
    const THRESHOLD = 16;

    // Email parts
    const COMPONENT_LOCALPART = 0;
    const COMPONENT_DOMAIN = 1;
    const COMPONENT_LITERAL = 2;
    const CONTEXT_COMMENT = 3;
    const CONTEXT_FWS = 4;
    const CONTEXT_QUOTEDSTRING = 5;
    const CONTEXT_QUOTEDPAIR = 6;

    // Miscellaneous string constants
    const STRING_AT = '@';
    const STRING_BACKSLASH = '\\';
    const STRING_DOT = '.';
    const STRING_DQUOTE = '"';
    const STRING_OPENPARENTHESIS = '(';
    const STRING_CLOSEPARENTHESIS = ')';
    const STRING_OPENSQBRACKET = '[';
    const STRING_CLOSESQBRACKET = ']';
    const STRING_HYPHEN = '-';
    const STRING_COLON = ':';
    const STRING_DOUBLECOLON = '::';
    const STRING_SP = ' ';
    const STRING_HTAB = "\t";
    const STRING_CR = "\r";
    const STRING_LF = "\n";
    const STRING_IPV6TAG = 'IPv6:';

    // US-ASCII visible characters not valid for atext
    // <http://tools.ietf.org/html/rfc5322#section-3.2.3>
    const STRING_SPECIALS = '()<>[]:;@\\,."';

    /**
     *
     * The email address being checked.
     *
     * @var string
     *
     */
    protected $email;

    /**
     *
     * Check DNS as part of validation?
     *
     * @var bool
     *
     */
    protected $checkDns;

    /**
     *
     * The validation threshold level.
     *
     * @var int
     *
     */
    protected $threshold;

    /**
     *
     * Diagnose errors?
     *
     * @var bool
     *
     */
    protected $diagnose;

    /**
     *
     * Has DNS been checked?
     *
     * @var bool
     *
     */
    protected $dnsChecked;

    /**
     *
     * The return status.
     *
     * @var int
     *
     */
    protected $returnStatus;

    /**
     *
     * The length of the email address being checked.
     *
     * @var int
     *
     */
    protected $rawLength;

    /**
     *
     * The current parser context.
     *
     * @var int
     *
     */
    protected $context;

    /**
     *
     * Parser context stack.
     *
     * @var array
     *
     */
    protected $contextStack;

    /**
     *
     * The prior parser context.
     *
     * @var int
     *
     */
    protected $contextPrior;

    /**
     *
     * The current token being parsed.
     *
     * @var string
     *
     */
    protected $token;

    /**
     *
     * The previous token being parsed.
     *
     * @var string
     *
     */
    protected $tokenPrior;

    /**
     *
     * The components of the address.
     *
     * @var array
     *
     */
    protected $parseData;

    /**
     *
     * The dot-atom elements of the address.
     *
     * @var array
     *
     */
    protected $atomList;

    /**
     *
     * Element count.
     *
     * @var int
     *
     */
    protected $elementCount;

    /**
     *
     * Element length.
     *
     * @var int
     *
     */
    protected $elementLen;

    /**
     *
     * Is a hyphen allowed?
     *
     * @var bool
     *
     */
    protected $hyphenFlag;

    /**
     *
     * CFWS can only appear at the end of the element
     *
     * @var bool
     *
     */
    protected $endOrDie;

    /**
     *
     * Current position in the email string.
     *
     * @var int
     *
     */
    protected $pos;

    /**
     *
     * Count of CRLF occurrences.
     *
     * @var null|int
     *
     */
    protected $crlfCount;

    /**
     *
     * The final status of email validation.
     *
     * @var int
     *
     */
    protected $finalStatus;

    /**
     *
     * Validates that the value is an email address.
     *
     * @param object $subject The subject to be filtered.
     *
     * @param string $field The subject field name.
     *
     * @return bool True if valid, false if not.
     *
     */
    public function __invoke($subject, $field)
    {
        $email = $subject->$field;
        if ($this->intl()) {
            $email = $this->idnToAscii($email);
        }
        return $this->isEmail($email);
    }

    /**
     *
     * Is the intl extension loaded?
     *
     * @return bool
     *
     */
    protected function intl()
    {
        return extension_loaded('intl');
    }

    /**
     *
     * Converts an international domain in the email address to ASCII.
     *
     * @param string $email The email address to check.
     *
     * @return string The email with the IDN converted to ASCII (if possible).
     *
     */
    protected function idnToAscii($email)
    {
        $parts = explode('@', $email);
        $domain = array_pop($parts);
        if (! $parts) {
            // no parts remaining, so no @ symbol, so not valid to begin with
            return $email;
        }

        // put the parts back together, with the domain part converted to ascii
        return implode('@', $parts) . '@' . idn_to_ascii($domain);
    }

    /**
     *
     * Checks that an email address conforms to RFCs 5321, 5322 and others,
     * allowing for international domain names when the intl extension is
     * loaded.
     *
     * @param string $email The email address to check.
     *
     * @param bool $checkDns Make a DNS check for MX records?
     *
     * @param mixed $errorlevel Determines the boundary between valid and
     * invalid addresses. Status codes above this number will be returned as-
     * is, status codes below will be returned as Email::VALID. Thus the
     * calling program can simply look for Email::VALID if it is only
     * interested in whether an address is valid or not. The errorlevel will
     * determine how "picky" is_email() is about the address. If omitted or
     * passed as false then isEmail() will return true or false rather than
     * an integer error or warning. N.B.: Note the difference between
     * $errorlevel = false and $errorlevel = 0.
     *
     */
    protected function isEmail($email, $checkDns = false, $errorlevel = false)
    {
        $this->reset($email, $checkDns, $errorlevel);
        $this->parse();
        $this->checkDns();
        $this->checkTld();
        $this->finalStatus();
        return ($this->diagnose)
            ? $this->finalStatus
            : ($this->finalStatus < Email::THRESHOLD);
    }

    /**
     *
     * Resets the validation rule for a new email address.
     *
     * @param string $email The email address to check.
     *
     * @param bool $checkDns Make a DNS check for MX records?
     *
     * @param mixed $errorlevel Determines the boundary between valid and
     * invalid addresses.
     *
     * @return null
     *
     */
    protected function reset($email, $checkDns, $errorlevel)
    {
        $this->email = $email;

        $this->checkDns = $checkDns;
        $this->dnsChecked = false;

        $this->setThresholdDiagnose($errorlevel);

        $this->returnStatus = array(Email::VALID);
        $this->rawLength = strlen($this->email);

        // Where we are
        $this->context = Email::COMPONENT_LOCALPART;

        // Where we have been
        $this->contextStack = array($this->context);

        // Where we just came from
        $this->contextPrior = Email::COMPONENT_LOCALPART;

        // The current character
        $this->token = '';

        // The previous character
        $this->tokenPrior = '';

        // For the components of the address
        $this->parseData = array(
            Email::COMPONENT_LOCALPART => '',
            Email::COMPONENT_DOMAIN => ''
        );

        // For the dot-atom elements of the address
        $this->atomList = array(
            Email::COMPONENT_LOCALPART => array(''),
            Email::COMPONENT_DOMAIN => array('')
        );

        $this->elementCount = 0;
        $this->elementLen = 0;

        // Hyphen cannot occur at the end of a subdomain
        $this->hyphenFlag = false;

        // CFWS can only appear at the end of the element
        $this->endOrDie = false;

        $this->finalStatus = null;

        $this->crlfCount = null;
    }

    /**
     *
     * Sets the $threshold and $diagnose properties.
     *
     * @param mixed $errorlevel Determines the boundary between valid and
     * invalid addresses.
     *
     * @return null
     *
     */
    protected function setThresholdDiagnose($errorlevel)
    {
        if (is_bool($errorlevel)) {
            $this->threshold = Email::VALID;
            $this->diagnose = (bool) $errorlevel;
            return;
        }

        $this->diagnose = true;

        switch ((int) $errorlevel) {
            case E_WARNING:
                // For backward compatibility
                $this->threshold = Email::THRESHOLD;
                break;
            case E_ERROR:
                // For backward compatibility
                $this->threshold = Email::VALID;
                break;
            default:
                $this->threshold = (int) $errorlevel;
        }
    }

    /**
     *
     * Parse the address into components, character by character.
     *
     * @return null
     *
     */
    protected function parse()
    {
        for ($this->pos = 0; $this->pos < $this->rawLength; $this->pos++) {
            $this->token = $this->email[$this->pos];
            $this->parseContext();
            if ((int) max($this->returnStatus) > Email::RFC5322) {
                // No point going on if we've got a fatal error
                break;
            }
        }
        $this->parseFinal();
    }

    /**
     *
     * Parse for the current context.
     *
     * @return null
     *
     */
    protected function parseContext()
    {
        switch ($this->context) {
            case Email::COMPONENT_LOCALPART:
                $this->parseComponentLocalPart();
                break;
            case Email::COMPONENT_DOMAIN:
                $this->parseComponentDomain();
                break;
            case Email::COMPONENT_LITERAL:
                $this->parseComponentLiteral();
                break;
            case Email::CONTEXT_QUOTEDSTRING:
                $this->parseContextQuotedString();
                break;
            case Email::CONTEXT_QUOTEDPAIR:
                $this->parseContextQuotedPair();
                break;
            case Email::CONTEXT_COMMENT:
                $this->parseContextComment();
                break;
            case Email::CONTEXT_FWS:
                $this->parseContextFws();
                break;
            default:
                throw new Exception("Unknown context: {$this->context}");
        }
    }

    /**
     *
     * Parse for the local part component.
     *
     * @return null
     *
     */
    protected function parseComponentLocalPart()
    {
        // http://tools.ietf.org/html/rfc5322#section-3.4.1
        //   local-part = dot-atom / quoted-string / obs-local-part
        //
        //   dot-atom = [CFWS] dot-atom-text [CFWS]
        //
        //   dot-atom-text = 1*atext *("." 1*atext)
        //
        //   quoted-string = [CFWS]
        //                       DQUOTE *([FWS] qcontent) [FWS] DQUOTE
        //                       [CFWS]
        //
        //   obs-local-part = word *("." word)
        //
        //   word = atom / quoted-string
        //
        //   atom = [CFWS] 1*atext [CFWS]
        switch ($this->token) {

            // Comment
            case Email::STRING_OPENPARENTHESIS:
                if ($this->elementLen === 0) {
                    // Comments are OK at the beginning of an element
                    $this->returnStatus[] = ($this->elementCount === 0)
                        ? Email::CFWS_COMMENT
                        : Email::DEPREC_COMMENT;
                } else {
                    // We can't start a comment in the middle of an element, so this better be the end
                    $this->returnStatus[] = Email::CFWS_COMMENT;
                    $this->endOrDie = true;
                }

                $this->contextStack[] = $this->context;
                $this->context = Email::CONTEXT_COMMENT;
                break;

            // Next dot-atom element
            case Email::STRING_DOT:
                if ($this->elementLen === 0) {
                    // Another dot, already?
                    // Fatal error
                    $this->returnStatus[] = ($this->elementCount === 0)
                        ? Email::ERR_DOT_START
                        : Email::ERR_CONSECUTIVEDOTS;
                } else {
                    // The entire local-part can be a quoted string for RFC 5321
                    // If it's just one atom that is quoted then it's an RFC 5322 obsolete form
                    if ($this->endOrDie) {
                        $this->returnStatus[] = Email::DEPREC_LOCALPART;
                    }
                }

                // CFWS & quoted strings are OK again now we're at the beginning of an element (although they are obsolete forms)
                $this->endOrDie = false;
                $this->elementLen = 0;
                $this->elementCount++;
                $this->parseData[Email::COMPONENT_LOCALPART] .= $this->token;
                $this->atomList[Email::COMPONENT_LOCALPART][$this->elementCount] = '';

                break;

            // Quoted string
            case Email::STRING_DQUOTE:
                if ($this->elementLen === 0) {
                    // The entire local-part can be a quoted string for RFC 5321
                    // If it's just one atom that is quoted then it's an RFC 5322 obsolete form
                    $this->returnStatus[] = ($this->elementCount === 0)
                        ? Email::RFC5321_QUOTEDSTRING
                        : Email::DEPREC_LOCALPART;

                    $this->parseData[Email::COMPONENT_LOCALPART] .= $this->token;
                    $this->atomList[Email::COMPONENT_LOCALPART][$this->elementCount] .= $this->token;
                    $this->elementLen++;
                    // Quoted string must be the entire element
                    $this->endOrDie = true;
                    $this->contextStack[] = $this->context;
                    $this->context = Email::CONTEXT_QUOTEDSTRING;
                } else {
                    // Fatal error
                    $this->returnStatus[] = Email::ERR_EXPECTING_ATEXT;
                }

                break;

            // Folding White Space
            case Email::STRING_CR:
            case Email::STRING_SP:
            case Email::STRING_HTAB:
                if (($this->token === Email::STRING_CR) && ((++$this->pos === $this->rawLength) || ($this->email[$this->pos] !== Email::STRING_LF))) {
                    // Fatal error
                    $this->returnStatus[] = Email::ERR_CR_NO_LF;
                    break;
                }

                if ($this->elementLen === 0) {
                    $this->returnStatus[] = ($this->elementCount === 0) ? Email::CFWS_FWS : Email::DEPREC_FWS;
                } else {
                    // We can't start FWS in the middle of an element, so this better be the end
                    $this->endOrDie = true;
                }

                $this->contextStack[] = $this->context;
                $this->context = Email::CONTEXT_FWS;
                $this->tokenPrior = $this->token;

                break;

            // @
            case Email::STRING_AT:
                // At this point we should have a valid local-part
                if (count($this->contextStack) !== 1) {
                    throw new Exception('Unexpected item on context stack');
                }

                if ($this->parseData[Email::COMPONENT_LOCALPART] === '') {
                    // Fatal error
                    $this->returnStatus[] = Email::ERR_NOLOCALPART;
                } elseif ($this->elementLen === 0) {
                    // Fatal error
                    $this->returnStatus[] = Email::ERR_DOT_END;
                } elseif (strlen($this->parseData[Email::COMPONENT_LOCALPART]) > 64) {
                    // http://tools.ietf.org/html/rfc5321#section-4.5.3.1.1
                    //   The maximum total length of a user name or other local-part is 64
                    //   octets.
                    $this->returnStatus[] = Email::RFC5322_LOCAL_TOOLONG;
                } elseif (($this->contextPrior === Email::CONTEXT_COMMENT) || ($this->contextPrior === Email::CONTEXT_FWS)) {
                    // http://tools.ietf.org/html/rfc5322#section-3.4.1
                    //   Comments and folding white space
                    //   SHOULD NOT be used around the "@" in the addr-spec.
                    //
                    // http://tools.ietf.org/html/rfc2119
                    // 4. SHOULD NOT   This phrase, or the phrase "NOT RECOMMENDED" mean that
                    //    there may exist valid reasons in particular circumstances when the
                    //    particular behavior is acceptable or even useful, but the full
                    //    implications should be understood and the case carefully weighed
                    //    before implementing any behavior described with this label.
                    $this->returnStatus[] = Email::DEPREC_CFWS_NEAR_AT;
                }

                // Clear everything down for the domain parsing
                $this->context = Email::COMPONENT_DOMAIN; // Where we are
                $this->contextStack = array($this->context); // Where we have been
                $this->elementCount = 0;
                $this->elementLen = 0;
                $this->endOrDie = false; // CFWS can only appear at the end of the element

                break;

            // atext
            default:
                // http://tools.ietf.org/html/rfc5322#section-3.2.3
                //    atext = ALPHA / DIGIT /    ; Printable US-ASCII
                //                        "!" / "#" /        ;  characters not including
                //                        "$" / "%" /        ;  specials.  Used for atoms.
                //                        "&" / "'" /
                //                        "*" / "+" /
                //                        "-" / "/" /
                //                        " = " / "?" /
                //                        "^" / "_" /
                //                        "`" / "{" /
                //                        "|" / "}" /
                //                        "~"
                if ($this->endOrDie) {
                    // We have encountered atext where it is no longer valid
                    switch ($this->contextPrior) {
                        case Email::CONTEXT_COMMENT:
                        case Email::CONTEXT_FWS:
                            $this->returnStatus[] = Email::ERR_ATEXT_AFTER_CFWS;
                            break;
                        case Email::CONTEXT_QUOTEDSTRING:
                            $this->returnStatus[] = Email::ERR_ATEXT_AFTER_QS;
                            break;
                        default:
                            throw new Exception("More atext found where none is allowed, but unrecognised prior context: {$this->contextPrior}");
                    }
                } else {
                    $this->contextPrior = $this->context;
                    $ord = ord($this->token);

                    if (($ord < 33) || ($ord > 126) || ($ord === 10) || (!is_bool(strpos(Email::STRING_SPECIALS, $this->token)))) {
                        // Fatal error
                        $this->returnStatus[] = Email::ERR_EXPECTING_ATEXT;
                    }

                    $this->parseData[Email::COMPONENT_LOCALPART] .= $this->token;
                    $this->atomList[Email::COMPONENT_LOCALPART][$this->elementCount] .= $this->token;
                    $this->elementLen++;
                }
        }
    }

    /**
     *
     * Parse for the domain component.
     *
     * @return null
     *
     */
    protected function parseComponentDomain()
    {
        // http://tools.ietf.org/html/rfc5322#section-3.4.1
        //   domain = dot-atom / domain-literal / obs-domain
        //
        //   dot-atom = [CFWS] dot-atom-text [CFWS]
        //
        //   dot-atom-text = 1*atext *("." 1*atext)
        //
        //   domain-literal = [CFWS] "[" *([FWS] dtext) [FWS] "]" [CFWS]
        //
        //   dtext = %d33-90 /          ; Printable US-ASCII
        //                       %d94-126 /         ;  characters not including
        //                       obs-dtext          ;  "[", "]", or "\"
        //
        //   obs-domain = atom *("." atom)
        //
        //   atom = [CFWS] 1*atext [CFWS]

        // http://tools.ietf.org/html/rfc5321#section-4.1.2
        //   Mailbox = Local-part "@" ( Domain / address-literal )
        //
        //   Domain = sub-domain *("." sub-domain)
        //
        //   address-literal = "[" ( IPv4-address-literal /
        //                    IPv6-address-literal /
        //                    General-address-literal ) "]"
        //                    ; See Section 4.1.3

        // http://tools.ietf.org/html/rfc5322#section-3.4.1
        //      Note: A liberal syntax for the domain portion of addr-spec is
        //      given here.  However, the domain portion contains addressing
        //      information specified by and used in other protocols (e.g.,
        //      [RFC1034], [RFC1035], [RFC1123], [RFC5321]).  It is therefore
        //      incumbent upon implementations to conform to the syntax of
        //      addresses for the context in which they are used.
        // is_email() author's note: it's not clear how to interpret this in
        // the context of a general email address validator. The conclusion I
        // have reached is this: "addressing information" must comply with
        // RFC 5321 (and in turn RFC 1035), anything that is "semantically
        // invisible" must comply only with RFC 5322.
        switch ($this->token) {

            // Comment
            case Email::STRING_OPENPARENTHESIS:
                if ($this->elementLen === 0) {
                    // Comments at the start of the domain are deprecated in the text
                    // Comments at the start of a subdomain are obs-domain
                    // (http://tools.ietf.org/html/rfc5322#section-3.4.1)
                    $this->returnStatus[] = ($this->elementCount === 0) ? Email::DEPREC_CFWS_NEAR_AT : Email::DEPREC_COMMENT;
                } else {
                    $this->returnStatus[] = Email::CFWS_COMMENT;
                    // We can't start a comment in the middle of an element, so this better be the end
                    $this->endOrDie = true;
                }

                $this->contextStack[] = $this->context;
                $this->context = Email::CONTEXT_COMMENT;
                break;

            // Next dot-atom element
            case Email::STRING_DOT:
                if ($this->elementLen === 0) {
                    // Another dot, already?
                    // Fatal error
                    $this->returnStatus[] = ($this->elementCount === 0) ? Email::ERR_DOT_START : Email::ERR_CONSECUTIVEDOTS;
                } elseif ($this->hyphenFlag) {
                    // Previous subdomain ended in a hyphen
                    $this->returnStatus[] = Email::ERR_DOMAINHYPHENEND;
                } else {
                    // Fatal error
                    //
                    // Nowhere in RFC 5321 does it say explicitly that the
                    // domain part of a Mailbox must be a valid domain according
                    // to the DNS standards set out in RFC 1035, but this *is*
                    // implied in several places. For instance, wherever the idea
                    // of host routing is discussed the RFC says that the domain
                    // must be looked up in the DNS. This would be nonsense unless
                    // the domain was designed to be a valid DNS domain. Hence we
                    // must conclude that the RFC 1035 restriction on label length
                    // also applies to RFC 5321 domains.
                    //
                    // http://tools.ietf.org/html/rfc1035#section-2.3.4
                    // labels          63 octets or less
                    if ($this->elementLen > 63) {
                        $this->returnStatus[] = Email::RFC5322_LABEL_TOOLONG;
                    }
                }

                // CFWS is OK again now we're at the beginning of an element (although it may be obsolete CFWS)
                $this->endOrDie = false;
                $this->elementLen = 0;
                $this->elementCount++;
                $this->atomList[Email::COMPONENT_DOMAIN][$this->elementCount] = '';
                $this->parseData[Email::COMPONENT_DOMAIN] .= $this->token;

                break;

            // Domain literal
            case Email::STRING_OPENSQBRACKET:
                if ($this->parseData[Email::COMPONENT_DOMAIN] === '') {
                    // Domain literal must be the only component
                    $this->endOrDie = true;
                    $this->elementLen++;
                    $this->contextStack[] = $this->context;
                    $this->context = Email::COMPONENT_LITERAL;
                    $this->parseData[Email::COMPONENT_DOMAIN] .= $this->token;
                    $this->atomList[Email::COMPONENT_DOMAIN][$this->elementCount] .= $this->token;
                    $this->parseData[Email::COMPONENT_LITERAL] = '';
                } else {
                    // Fatal error
                    $this->returnStatus[] = Email::ERR_EXPECTING_ATEXT;
                }

                break;

            // Folding White Space
            case Email::STRING_CR:
            case Email::STRING_SP:
            case Email::STRING_HTAB:
                if (($this->token === Email::STRING_CR) && ((++$this->pos === $this->rawLength) || ($this->email[$this->pos] !== Email::STRING_LF))) {
                    // Fatal error
                    $this->returnStatus[] = Email::ERR_CR_NO_LF;
                    break;
                }

                if ($this->elementLen === 0) {
                    $this->returnStatus[] = ($this->elementCount === 0) ? Email::DEPREC_CFWS_NEAR_AT : Email::DEPREC_FWS;
                } else {
                    $this->returnStatus[] = Email::CFWS_FWS;
                    // We can't start FWS in the middle of an element, so this better be the end
                    $this->endOrDie = true;
                }

                $this->contextStack[] = $this->context;
                $this->context = Email::CONTEXT_FWS;
                $this->tokenPrior = $this->token;
                break;

            // atext
            default:
                // RFC 5322 allows any atext...
                // http://tools.ietf.org/html/rfc5322#section-3.2.3
                //    atext = ALPHA / DIGIT /    ; Printable US-ASCII
                //                        "!" / "#" /        ;  characters not including
                //                        "$" / "%" /        ;  specials.  Used for atoms.
                //                        "&" / "'" /
                //                        "*" / "+" /
                //                        "-" / "/" /
                //                        " = " / "?" /
                //                        "^" / "_" /
                //                        "`" / "{" /
                //                        "|" / "}" /
                //                        "~"

                // But RFC 5321 only allows letter-digit-hyphen to comply with DNS rules (RFCs 1034 & 1123)
                // http://tools.ietf.org/html/rfc5321#section-4.1.2
                //   sub-domain = Let-dig [Ldh-str]
                //
                //   Let-dig = ALPHA / DIGIT
                //
                //   Ldh-str = *( ALPHA / DIGIT / "-" ) Let-dig
                //
                if ($this->endOrDie) {
                    // We have encountered atext where it is no longer valid
                    switch ($this->contextPrior) {
                        case Email::CONTEXT_COMMENT:
                        case Email::CONTEXT_FWS:
                            $this->returnStatus[] = Email::ERR_ATEXT_AFTER_CFWS;
                            break;
                        case Email::COMPONENT_LITERAL:
                            $this->returnStatus[] = Email::ERR_ATEXT_AFTER_DOMLIT;
                            break;
                        default:
                            throw new Exception("More atext found where none is allowed, but unrecognised prior context: {$this->contextPrior}");
                    }
                }

                $ord = ord($this->token);

                // Assume this token isn't a hyphen unless we discover it is
                $this->hyphenFlag = false;

                if (($ord < 33) || ($ord > 126) || (!is_bool(strpos(Email::STRING_SPECIALS, $this->token)))) {
                    // Fatal error
                    $this->returnStatus[] = Email::ERR_EXPECTING_ATEXT;
                } elseif ($this->token === Email::STRING_HYPHEN) {
                    if ($this->elementLen === 0) {
                        // Hyphens can't be at the beginning of a subdomain
                        // Fatal error
                        $this->returnStatus[] = Email::ERR_DOMAINHYPHENSTART;
                    }
                    $this->hyphenFlag = true;
                } elseif (!(($ord > 47 && $ord < 58) || ($ord > 64 && $ord < 91) || ($ord > 96 && $ord < 123))) {
                    // Not an RFC 5321 subdomain, but still OK by RFC 5322
                    $this->returnStatus[] = Email::RFC5322_DOMAIN;
                }

                $this->parseData[Email::COMPONENT_DOMAIN] .= $this->token;
                $this->atomList[Email::COMPONENT_DOMAIN][$this->elementCount] .= $this->token;
                $this->elementLen++;
        }
    }

    /**
     *
     * Parse for a literal component.
     *
     * @return null
     *
     */
    protected function parseComponentLiteral()
    {
        // http://tools.ietf.org/html/rfc5322#section-3.4.1
        //   domain-literal = [CFWS] "[" *([FWS] dtext) [FWS] "]" [CFWS]
        //
        //   dtext = %d33-90 /          ; Printable US-ASCII
        //                       %d94-126 /         ;  characters not including
        //                       obs-dtext          ;  "[", "]", or "\"
        //
        //   obs-dtext = obs-NO-WS-CTL / quoted-pair
        switch ($this->token) {

            // End of domain literal
            case Email::STRING_CLOSESQBRACKET:
                if ((int) max($this->returnStatus) < Email::DEPREC) {
                    // Could be a valid RFC 5321 address literal, so let's check

                    // http://tools.ietf.org/html/rfc5321#section-4.1.2
                    //   address-literal = "[" ( IPv4-address-literal /
                    //                    IPv6-address-literal /
                    //                    General-address-literal ) "]"
                    //                    ; See Section 4.1.3
                    //
                    // http://tools.ietf.org/html/rfc5321#section-4.1.3
                    //   IPv4-address-literal = Snum 3("."  Snum)
                    //
                    //   IPv6-address-literal = "IPv6:" IPv6-addr
                    //
                    //   General-address-literal = Standardized-tag ":" 1*dcontent
                    //
                    //   Standardized-tag = Ldh-str
                    //                     ; Standardized-tag MUST be specified in a
                    //                     ; Standards-Track RFC and registered with IANA
                    //
                    //   dcontent = %d33-90 / ; Printable US-ASCII
                    //                  %d94-126 ; excl. "[", "\", "]"
                    //
                    //   Snum = 1*3DIGIT
                    //                  ; representing a decimal integer
                    //                  ; value in the range 0 through 255
                    //
                    //   IPv6-addr = IPv6-full / IPv6-comp / IPv6v4-full / IPv6v4-comp
                    //
                    //   IPv6-hex = 1*4HEXDIG
                    //
                    //   IPv6-full = IPv6-hex 7(":" IPv6-hex)
                    //
                    //   IPv6-comp = [IPv6-hex *5(":" IPv6-hex)] "::"
                    //                  [IPv6-hex *5(":" IPv6-hex)]
                    //                  ; The "::" represents at least 2 16-bit groups of
                    //                  ; zeros.  No more than 6 groups in addition to the
                    //                  ; "::" may be present.
                    //
                    //   IPv6v4-full = IPv6-hex 5(":" IPv6-hex) ":" IPv4-address-literal
                    //
                    //   IPv6v4-comp = [IPv6-hex *3(":" IPv6-hex)] "::"
                    //                  [IPv6-hex *3(":" IPv6-hex) ":"]
                    //                  IPv4-address-literal
                    //                  ; The "::" represents at least 2 16-bit groups of
                    //                  ; zeros.  No more than 4 groups in addition to the
                    //                  ; "::" and IPv4-address-literal may be present.
                    //
                    // is_email() author's note: We can't use ip2long() to validate
                    // IPv4 addresses because it accepts abbreviated addresses
                    // (xxx.xxx.xxx), expanding the last group to complete the address.
                    // filter_var() validates IPv6 address inconsistently (up to PHP 5.3.3
                    // at least) -- see http://bugs.php.net/bug.php?id = 53236 for example
                    $max_groups = 8;
                    $matchesIP = array();
                    $index = false;
                    $addressliteral = $this->parseData[Email::COMPONENT_LITERAL];

                    // Extract IPv4 part from the end of the address-literal (if there is one)
                    if (preg_match('/\\b(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/', $addressliteral, $matchesIP) > 0) {
                        $index = strrpos($addressliteral, $matchesIP[0]);
                        if ($index !== 0) {
                            // Convert IPv4 part to IPv6 format for further testing
                            $addressliteral = substr($addressliteral, 0, $index) . '0:0';
                        }
                    }

                    if ($index === 0) {
                        // Nothing there except a valid IPv4 address, so...
                        $this->returnStatus[] = Email::RFC5321_ADDRESSLITERAL;
                    } elseif (strncasecmp($addressliteral, Email::STRING_IPV6TAG, 5) !== 0) {
                        $this->returnStatus[] = Email::RFC5322_DOMAINLITERAL;
                    } else {
                        $IPv6 = substr($addressliteral, 5);
                        // Revision 2.7: Daniel Marschall's new IPv6 testing strategy
                        $matchesIP = explode(Email::STRING_COLON, $IPv6);
                        $groupCount = count($matchesIP);
                        $index = strpos($IPv6, Email::STRING_DOUBLECOLON);

                        if ($index === false) {
                            // We need exactly the right number of groups
                            if ($groupCount !== $max_groups) {
                                $this->returnStatus[] = Email::RFC5322_IPV6_GRPCOUNT;
                            }
                        } else {
                            if ($index !== strrpos($IPv6, Email::STRING_DOUBLECOLON)) {
                                $this->returnStatus[] = Email::RFC5322_IPV6_2X2XCOLON;
                            } else {
                                if ($index === 0 || $index === (strlen($IPv6) - 2)) {
                                    // RFC 4291 allows :: at the start or end of an address with 7 other groups in addition
                                    $max_groups++;
                                }

                                if ($groupCount > $max_groups) {
                                    $this->returnStatus[] = Email::RFC5322_IPV6_MAXGRPS;
                                } elseif ($groupCount === $max_groups) {
                                    // Eliding a single "::"
                                    $this->returnStatus[] = Email::RFC5321_IPV6DEPRECATED;
                                }
                            }
                        }

                        // Revision 2.7: Daniel Marschall's new IPv6 testing strategy
                        if ((substr($IPv6, 0,  1) === Email::STRING_COLON) && (substr($IPv6, 1,  1) !== Email::STRING_COLON)) {
                            // Address starts with a single colon
                            $this->returnStatus[] = Email::RFC5322_IPV6_COLONSTRT;
                        } elseif ((substr($IPv6, -1) === Email::STRING_COLON) && (substr($IPv6, -2, 1) !== Email::STRING_COLON)) {
                            // Address ends with a single colon
                            $this->returnStatus[] = Email::RFC5322_IPV6_COLONEND;
                        } elseif (count(preg_grep('/^[0-9A-Fa-f]{0,4}$/', $matchesIP, PREG_GREP_INVERT)) !== 0) {
                            // Check for unmatched characters
                            $this->returnStatus[] = Email::RFC5322_IPV6_BADCHAR;
                        } else {
                            $this->returnStatus[] = Email::RFC5321_ADDRESSLITERAL;
                        }
                    }
                } else {
                    $this->returnStatus[] = Email::RFC5322_DOMAINLITERAL;
                }

                $this->parseData[Email::COMPONENT_DOMAIN] .= $this->token;
                $this->atomList[Email::COMPONENT_DOMAIN][$this->elementCount] .= $this->token;
                $this->elementLen++;
                $this->contextPrior = $this->context;
                $this->context = (int) array_pop($this->contextStack);
                break;

            case Email::STRING_BACKSLASH:
                $this->returnStatus[] = Email::RFC5322_DOMLIT_OBSDTEXT;
                $this->contextStack[] = $this->context;
                $this->context = Email::CONTEXT_QUOTEDPAIR;
                break;

            // Folding White Space
            case Email::STRING_CR:
            case Email::STRING_SP:
            case Email::STRING_HTAB:
                if (($this->token === Email::STRING_CR) && ((++$this->pos === $this->rawLength) || ($this->email[$this->pos] !== Email::STRING_LF))) {
                    // Fatal error
                    $this->returnStatus[] = Email::ERR_CR_NO_LF;
                    break;
                }

                $this->returnStatus[] = Email::CFWS_FWS;

                $this->contextStack[] = $this->context;
                $this->context = Email::CONTEXT_FWS;
                $this->tokenPrior = $this->token;
                break;

            // dtext
            default:
                // http://tools.ietf.org/html/rfc5322#section-3.4.1
                //   dtext = %d33-90 /          ; Printable US-ASCII
                //                       %d94-126 /         ;  characters not including
                //                       obs-dtext          ;  "[", "]", or "\"
                //
                //   obs-dtext = obs-NO-WS-CTL / quoted-pair
                //
                //   obs-NO-WS-CTL = %d1-8 /            ; US-ASCII control
                //                       %d11 /             ;  characters that do not
                //                       %d12 /             ;  include the carriage
                //                       %d14-31 /          ;  return, line feed, and
                //                       %d127              ;  white space characters
                $ord = ord($this->token);

                // CR, LF, SP & HTAB have already been parsed above
                if (($ord > 127) || ($ord === 0) || ($this->token === Email::STRING_OPENSQBRACKET)) {
                    $this->returnStatus[] = Email::ERR_EXPECTING_DTEXT; // Fatal error
                    break;
                } elseif (($ord < 33) || ($ord === 127)) {
                    $this->returnStatus[] = Email::RFC5322_DOMLIT_OBSDTEXT;
                }

                $this->parseData[Email::COMPONENT_LITERAL] .= $this->token;
                $this->parseData[Email::COMPONENT_DOMAIN] .= $this->token;
                $this->atomList[Email::COMPONENT_DOMAIN][$this->elementCount] .= $this->token;
                $this->elementLen++;
        }
    }

    /**
     *
     * Parse for a quoted-string context.
     *
     * @return null
     *
     */
    protected function parseContextQuotedString()
    {
        // http://tools.ietf.org/html/rfc5322#section-3.2.4
        //   quoted-string = [CFWS]
        //                       DQUOTE *([FWS] qcontent) [FWS] DQUOTE
        //                       [CFWS]
        //
        //   qcontent = qtext / quoted-pair
        switch ($this->token) {

            // Quoted pair
            case Email::STRING_BACKSLASH:
                $this->contextStack[] = $this->context;
                $this->context = Email::CONTEXT_QUOTEDPAIR;
                break;

            // Folding White Space
            // Inside a quoted string, spaces are allowed as regular characters.
            // It's only FWS if we include HTAB or CRLF
            case Email::STRING_CR:
            case Email::STRING_HTAB:
                if (($this->token === Email::STRING_CR) && ((++$this->pos === $this->rawLength) || ($this->email[$this->pos] !== Email::STRING_LF))) {
                    // Fatal error
                    $this->returnStatus[] = Email::ERR_CR_NO_LF;
                    break;
                }

                // http://tools.ietf.org/html/rfc5322#section-3.2.2
                //   Runs of FWS, comment, or CFWS that occur between lexical tokens in a
                //   structured header field are semantically interpreted as a single
                //   space character.

                // http://tools.ietf.org/html/rfc5322#section-3.2.4
                //   the CRLF in any FWS/CFWS that appears within the quoted-string [is]
                //   semantically "invisible" and therefore not part of the quoted-string
                $this->parseData[Email::COMPONENT_LOCALPART] .= Email::STRING_SP;
                $this->atomList[Email::COMPONENT_LOCALPART][$this->elementCount] .= Email::STRING_SP;
                $this->elementLen++;

                $this->returnStatus[] = Email::CFWS_FWS;
                $this->contextStack[] = $this->context;
                $this->context = Email::CONTEXT_FWS;
                $this->tokenPrior = $this->token;
                break;

            // End of quoted string
            case Email::STRING_DQUOTE:
                $this->parseData[Email::COMPONENT_LOCALPART] .= $this->token;
                $this->atomList[Email::COMPONENT_LOCALPART][$this->elementCount] .= $this->token;
                $this->elementLen++;
                $this->contextPrior = $this->context;
                $this->context = (int) array_pop($this->contextStack);
                break;

            // qtext
            default:
                // http://tools.ietf.org/html/rfc5322#section-3.2.4
                //   qtext = %d33 /             ; Printable US-ASCII
                //                       %d35-91 /          ;  characters not including
                //                       %d93-126 /         ;  "\" or the quote character
                //                       obs-qtext
                //
                //   obs-qtext = obs-NO-WS-CTL
                //
                //   obs-NO-WS-CTL = %d1-8 /            ; US-ASCII control
                //                       %d11 /             ;  characters that do not
                //                       %d12 /             ;  include the carriage
                //                       %d14-31 /          ;  return, line feed, and
                //                       %d127              ;  white space characters
                $ord = ord($this->token);

                if (($ord > 127) || ($ord === 0) || ($ord === 10)) {
                    // Fatal error
                    $this->returnStatus[] = Email::ERR_EXPECTING_QTEXT;
                } elseif (($ord < 32) || ($ord === 127)) {
                    $this->returnStatus[] = Email::DEPREC_QTEXT;
                }

                $this->parseData[Email::COMPONENT_LOCALPART] .= $this->token;
                $this->atomList[Email::COMPONENT_LOCALPART][$this->elementCount] .= $this->token;
                $this->elementLen++;
        }

        // http://tools.ietf.org/html/rfc5322#section-3.4.1
        //   If the string can be represented as a dot-atom (that is, it contains
        //   no characters other than atext characters or "." surrounded by atext
        //   characters), then the dot-atom form SHOULD be used and the quoted-
        //   string form SHOULD NOT be used.
        //
        // TODO
        //
    }

    /**
     *
     * Parse for a quoted-pair context.
     *
     * @return null
     *
     */
    protected function parseContextQuotedPair()
    {
        // http://tools.ietf.org/html/rfc5322#section-3.2.1
        //   quoted-pair = ("\" (VCHAR / WSP)) / obs-qp
        //
        //   VCHAR = %d33-126            ; visible (printing) characters
        //   WSP = SP / HTAB           ; white space
        //
        //   obs-qp = "\" (%d0 / obs-NO-WS-CTL / LF / CR)
        //
        //   obs-NO-WS-CTL = %d1-8 /            ; US-ASCII control
        //                       %d11 /             ;  characters that do not
        //                       %d12 /             ;  include the carriage
        //                       %d14-31 /          ;  return, line feed, and
        //                       %d127              ;  white space characters
        //
        // i.e. obs-qp = "\" (%d0-8, %d10-31 / %d127)
        $ord = ord($this->token);

        if ($ord > 127) {
            $this->returnStatus[] = Email::ERR_EXPECTING_QPAIR;
        } elseif ((($ord < 31) && ($ord !== 9)) || ($ord === 127)) {
            // SP & HTAB are allowed
            // Fatal error
            $this->returnStatus[] = Email::DEPREC_QP;
        }

        // At this point we know where this qpair occurred so
        // we could check to see if the character actually
        // needed to be quoted at all.
        // http://tools.ietf.org/html/rfc5321#section-4.1.2
        //   the sending system SHOULD transmit the
        //   form that uses the minimum quoting possible.
        //
        // TODO: check whether the character needs to be quoted (escaped) in this context
        //
        $this->contextPrior = $this->context;
        $this->context = (int) array_pop($this->contextStack); // End of qpair
        $this->token = Email::STRING_BACKSLASH . $this->token;

        switch ($this->context) {
            case Email::CONTEXT_COMMENT:
                break;
            case Email::CONTEXT_QUOTEDSTRING:
                $this->parseData[Email::COMPONENT_LOCALPART] .= $this->token;
                $this->atomList[Email::COMPONENT_LOCALPART][$this->elementCount] .= $this->token;
                // The maximum sizes specified by RFC 5321 are octet counts, so we must include the backslash
                $this->elementLen += 2;
                break;
            case Email::COMPONENT_LITERAL:
                $this->parseData[Email::COMPONENT_DOMAIN] .= $this->token;
                $this->atomList[Email::COMPONENT_DOMAIN][$this->elementCount] .= $this->token;
                // The maximum sizes specified by RFC 5321 are octet counts, so we must include the backslash
                $this->elementLen += 2;
                break;
            default:
                throw new Exception("Quoted pair logic invoked in an invalid context: {$this->context}");
        }
    }

    /**
     *
     * Parse for a comment context.
     *
     * @return null
     *
     */
    protected function parseContextComment()
    {
        // http://tools.ietf.org/html/rfc5322#section-3.2.2
        //   comment = "(" *([FWS] ccontent) [FWS] ")"
        //
        //   ccontent = ctext / quoted-pair / comment
        switch ($this->token) {

            // Nested comment
            case Email::STRING_OPENPARENTHESIS:
                // Nested comments are OK
                $this->contextStack[] = $this->context;
                $this->context = Email::CONTEXT_COMMENT;
                break;

            // End of comment
            case Email::STRING_CLOSEPARENTHESIS:
                $this->contextPrior = $this->context;
                $this->context = (int) array_pop($this->contextStack);

                // http://tools.ietf.org/html/rfc5322#section-3.2.2
                //   Runs of FWS, comment, or CFWS that occur between lexical tokens in a
                //   structured header field are semantically interpreted as a single
                //   space character.
                //
                // is_email() author's note: This *cannot* mean that we must add a
                // space to the address wherever CFWS appears. This would result in
                // any addr-spec that had CFWS outside a quoted string being invalid
                // for RFC 5321.
                //
                // if (($this->context === Email::COMPONENT_LOCALPART) || ($this->context === Email::COMPONENT_DOMAIN)) {
                //     $this->parseData[$this->context] .= Email::STRING_SP;
                //     $this->atomList[$this->context][$this->elementCount] .= Email::STRING_SP;
                //     $this->elementLen++;
                // }

                break;

            // Quoted pair
            case Email::STRING_BACKSLASH:
                $this->contextStack[] = $this->context;
                $this->context = Email::CONTEXT_QUOTEDPAIR;
                break;

            // Folding White Space
            case Email::STRING_CR:
            case Email::STRING_SP:
            case Email::STRING_HTAB:
                if (($this->token === Email::STRING_CR) && ((++$this->pos === $this->rawLength) || ($this->email[$this->pos] !== Email::STRING_LF))) {
                    // Fatal error
                    $this->returnStatus[] = Email::ERR_CR_NO_LF;
                    break;
                }

                $this->returnStatus[] = Email::CFWS_FWS;

                $this->contextStack[] = $this->context;
                $this->context = Email::CONTEXT_FWS;
                $this->tokenPrior = $this->token;
                break;

            // ctext
            default:
                // http://tools.ietf.org/html/rfc5322#section-3.2.3
                //   ctext = %d33-39 /          ; Printable US-ASCII
                //                       %d42-91 /          ;  characters not including
                //                       %d93-126 /         ;  "(", ")", or "\"
                //                       obs-ctext
                //
                //   obs-ctext = obs-NO-WS-CTL
                //
                //   obs-NO-WS-CTL = %d1-8 /            ; US-ASCII control
                //                       %d11 /             ;  characters that do not
                //                       %d12 /             ;  include the carriage
                //                       %d14-31 /          ;  return, line feed, and
                //                       %d127              ;  white space characters
                $ord = ord($this->token);

                if (($ord > 127) || ($ord === 0) || ($ord === 10)) {
                    $this->returnStatus[] = Email::ERR_EXPECTING_CTEXT; // Fatal error
                    break;
                } elseif (($ord < 32) || ($ord === 127)) {
                    $this->returnStatus[] = Email::DEPREC_CTEXT;
                }
        }
    }

    /**
     *
     * Parse for a folding-white-space context.
     *
     * @return null
     *
     */
    protected function parseContextFws()
    {
        // http://tools.ietf.org/html/rfc5322#section-3.2.2
        //   FWS = ([*WSP CRLF] 1*WSP) /  obs-FWS
        //                                          ; Folding white space

        // But note the erratum:
        // http://www.rfc-editor.org/errata_search.php?rfc = 5322&eid = 1908:
        //   In the obsolete syntax, any amount of folding white space MAY be
        //   inserted where the obs-FWS rule is allowed.  This creates the
        //   possibility of having two consecutive "folds" in a line, and
        //   therefore the possibility that a line which makes up a folded header
        //   field could be composed entirely of white space.
        //
        //   obs-FWS = 1*([CRLF] WSP)
        if ($this->tokenPrior === Email::STRING_CR) {
            if ($this->token === Email::STRING_CR) {
                // Fatal error
                $this->returnStatus[] = Email::ERR_FWS_CRLF_X2;
                return;
            }

            if (isset($this->crlfCount)) {
                if (++$this->crlfCount > 1) {
                    $this->returnStatus[] = Email::DEPREC_FWS;
                } // Multiple folds = obsolete FWS
            } else {
                $this->crlfCount = 1;
            }
        }

        switch ($this->token) {
            case Email::STRING_CR:
                if ((++$this->pos === $this->rawLength) || ($this->email[$this->pos] !== Email::STRING_LF)) {
                     // Fatal error
                    $this->returnStatus[] = Email::ERR_CR_NO_LF;
                }
                break;

            case Email::STRING_SP:
            case Email::STRING_HTAB:
                break;

            default:
                if ($this->tokenPrior === Email::STRING_CR) {
                    // Fatal error
                    $this->returnStatus[] = Email::ERR_FWS_CRLF_END;
                    break;
                }

                if (isset($this->crlfCount)) {
                    unset($this->crlfCount);
                }

                $this->contextPrior = $this->context;
                $this->context = (int) array_pop($this->contextStack); // End of FWS

                // http://tools.ietf.org/html/rfc5322#section-3.2.2
                //   Runs of FWS, comment, or CFWS that occur between lexical tokens in a
                //   structured header field are semantically interpreted as a single
                //   space character.
                //
                // is_email() author's note: This *cannot* mean that we must add a
                // space to the address wherever CFWS appears. This would result in
                // any addr-spec that had CFWS outside a quoted string being invalid
                // for RFC 5321.
                //
                // if (($this->context === Email::COMPONENT_LOCALPART) || ($this->context === Email::COMPONENT_DOMAIN)) {
                //     $this->parseData[$this->context] .= Email::STRING_SP;
                //     $this->atomList[$this->context][$this->elementCount] .= Email::STRING_SP;
                //     $this->elementLen++;
                // }

                $this->pos--; // Look at this token again in the parent context
        }

        $this->tokenPrior = $this->token;
    }

    /**
     *
     * Final wrap-up parsing.
     *
     * @return null
     *
     */
    protected function parseFinal()
    {
        // Some simple final tests
        if ((int) max($this->returnStatus) < Email::RFC5322) {
            if ($this->context === Email::CONTEXT_QUOTEDSTRING) {
                // Fatal error
                $this->returnStatus[] = Email::ERR_UNCLOSEDQUOTEDSTR;
            } elseif ($this->context === Email::CONTEXT_QUOTEDPAIR) {
                // Fatal error
                $this->returnStatus[] = Email::ERR_BACKSLASHEND;
            } elseif ($this->context === Email::CONTEXT_COMMENT) {
                // Fatal error
                $this->returnStatus[] = Email::ERR_UNCLOSEDCOMMENT;
            } elseif ($this->context === Email::COMPONENT_LITERAL) {
                // Fatal error
                $this->returnStatus[] = Email::ERR_UNCLOSEDDOMLIT;
            } elseif ($this->token === Email::STRING_CR) {
                // Fatal error
                $this->returnStatus[] = Email::ERR_FWS_CRLF_END;
            } elseif ($this->parseData[Email::COMPONENT_DOMAIN] === '') {
                // Fatal error
                $this->returnStatus[] = Email::ERR_NODOMAIN;
            } elseif ($this->elementLen === 0) {
                // Fatal error
                $this->returnStatus[] = Email::ERR_DOT_END;
            } elseif ($this->hyphenFlag) {
                // Fatal error
                $this->returnStatus[] = Email::ERR_DOMAINHYPHENEND;
            } elseif (strlen($this->parseData[Email::COMPONENT_DOMAIN]) > 255) {
                // http://tools.ietf.org/html/rfc5321#section-4.5.3.1.2
                //   The maximum total length of a domain name or number is 255 octets.
                $this->returnStatus[] = Email::RFC5322_DOMAIN_TOOLONG;
            } elseif (strlen($this->parseData[Email::COMPONENT_LOCALPART] . Email::STRING_AT . $this->parseData[Email::COMPONENT_DOMAIN]) > 254) {
                // http://tools.ietf.org/html/rfc5321#section-4.1.2
                //   Forward-path = Path
                //
                //   Path = "<" [ A-d-l ":" ] Mailbox ">"
                //
                // http://tools.ietf.org/html/rfc5321#section-4.5.3.1.3
                //   The maximum total length of a reverse-path or forward-path is 256
                //   octets (including the punctuation and element separators).
                //
                // Thus, even without (obsolete) routing information, the Mailbox can
                // only be 254 characters long. This is confirmed by this verified
                // erratum to RFC 3696:
                //
                // http://www.rfc-editor.org/errata_search.php?rfc = 3696&eid = 1690
                //   However, there is a restriction in RFC 2821 on the length of an
                //   address in MAIL and RCPT commands of 254 characters.  Since addresses
                //   that do not fit in those fields are not normally useful, the upper
                //   limit on address lengths should normally be considered to be 254.
                $this->returnStatus[] = Email::RFC5322_TOOLONG;
            } elseif ($this->elementLen > 63) {
                // http://tools.ietf.org/html/rfc1035#section-2.3.4
                // labels          63 octets or less
                $this->returnStatus[] = Email::RFC5322_LABEL_TOOLONG;
            }
        }
    }

    /**
     *
     * Make a DNS check on the MX record, if requested.
     *
     * @return null
     *
     */
    protected function checkDns()
    {
        // Check DNS?
        if ($this->checkDns && ((int) max($this->returnStatus) < Email::DNSWARN) && function_exists('dns_get_record')) {
            // http://tools.ietf.org/html/rfc5321#section-2.3.5
            //   Names that can
            //   be resolved to MX RRs or address (i.e., A or AAAA) RRs (as discussed
            //   in Section 5) are permitted, as are CNAME RRs whose targets can be
            //   resolved, in turn, to MX or address RRs.
            //
            // http://tools.ietf.org/html/rfc5321#section-5.1
            //   The lookup first attempts to locate an MX record associated with the
            //   name.  If a CNAME record is found, the resulting name is processed as
            //   if it were the initial name. ... If an empty list of MXs is returned,
            //   the address is treated as if it was associated with an implicit MX
            //   RR, with a preference of 0, pointing to that host.
            //
            // is_email() author's note: We will regard the existence of a CNAME to be
            // sufficient evidence of the domain's existence. For performance reasons
            // we will not repeat the DNS lookup for the CNAME's target, but we will
            // raise a warning because we didn't immediately find an MX record.
            if ($this->elementCount === 0) {
                // Checking TLD DNS seems to work only if you explicitly check from the root
                $this->parseData[Email::COMPONENT_DOMAIN] .= '.';
            }

            $result = @dns_get_record($this->parseData[Email::COMPONENT_DOMAIN], DNS_MX); // Not using checkdnsrr because of a suspected bug in PHP 5.3 (http://bugs.php.net/bug.php?id = 51844)

            if ((is_bool($result) && !(bool) $result)) {
                // Domain can't be found in DNS
                $this->returnStatus[] = Email::DNSWARN_NO_RECORD;
            } else {
                if (count($result) === 0) {
                    // MX-record for domain can't be found
                    $this->returnStatus[] = Email::DNSWARN_NO_MX_RECORD;
                    $result = @dns_get_record($this->parseData[Email::COMPONENT_DOMAIN], DNS_A + DNS_CNAME);
                    if (count($result) === 0) {
                        // No usable records for the domain can be found
                        $this->returnStatus[] = Email::DNSWARN_NO_RECORD;
                    }
                } else {
                    $this->dnsChecked = true;
                }
            }
        }
    }

    /**
     *
     * Check the top-level domain of the address.
     *
     * @return null
     *
     */
    protected function checkTld()
    {
        // Check for TLD addresses
        // -----------------------
        // TLD addresses are specifically allowed in RFC 5321 but they are
        // unusual to say the least. We will allocate a separate
        // status to these addresses on the basis that they are more likely
        // to be typos than genuine addresses (unless we've already
        // established that the domain does have an MX record)
        //
        // http://tools.ietf.org/html/rfc5321#section-2.3.5
        //   In the case
        //   of a top-level domain used by itself in an email address, a single
        //   string is used without any dots.  This makes the requirement,
        //   described in more detail below, that only fully-qualified domain
        //   names appear in SMTP transactions on the public Internet,
        //   particularly important where top-level domains are involved.
        //
        // TLD format
        // ----------
        // The format of TLDs has changed a number of times. The standards
        // used by IANA have been largely ignored by ICANN, leading to
        // confusion over the standards being followed. These are not defined
        // anywhere, except as a general component of a DNS host name (a label).
        // However, this could potentially lead to 123.123.123.123 being a
        // valid DNS name (rather than an IP address) and thereby creating
        // an ambiguity. The most authoritative statement on TLD formats that
        // the author can find is in a (rejected!) erratum to RFC 1123
        // submitted by John Klensin, the author of RFC 5321:
        //
        // http://www.rfc-editor.org/errata_search.php?rfc = 1123&eid = 1353
        //   However, a valid host name can never have the dotted-decimal
        //   form #.#.#.#, since this change does not permit the highest-level
        //   component label to start with a digit even if it is not all-numeric.
        if (!$this->dnsChecked && ((int) max($this->returnStatus) < Email::DNSWARN)) {
            if ($this->elementCount === 0) {
                $this->returnStatus[] = Email::RFC5321_TLD;
            }

            if (is_numeric($this->atomList[Email::COMPONENT_DOMAIN][$this->elementCount][0])) {
                $this->returnStatus[] = Email::RFC5321_TLDNUMERIC;
            }
        }
    }

    /**
     *
     * Sets the final status and return status.
     *
     * @return null
     *
     */
    protected function finalStatus()
    {
        $this->returnStatus = array_unique($this->returnStatus);
        $this->finalStatus = (int) max($this->returnStatus);

        if (count($this->returnStatus) !== 1) {
            // remove redundant Email::VALID
            array_shift($this->returnStatus);
        }

        $this->parseData['status'] = $this->returnStatus;

        if ($this->finalStatus < $this->threshold) {
            $this->finalStatus = Email::VALID;
        }
    }
}
