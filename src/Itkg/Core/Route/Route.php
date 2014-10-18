<?php

namespace Itkg\Core\Route;

/**
 * Routes are used to determine the controller and action for a requested URI.
 * Every route generates a regular expression which is used to match a URI
 * and a route. Routes may also contain keys which can be used to set the
 * controller, action, and parameters.
 *
 * Each <key> will be translated to a regular expression using a default
 * regular expression pattern. You can override the default pattern by providing
 * a pattern for the key:
 *
 * // This route will only match when <id> is a digit
 * Pelican_Route::factory('user/edit/<id>', array('id' => '\d+'));
 *
 * // This route will match when <path> is anything
 * Pelican_Route::factory('<path>', array('path' => '.*'));
 *
 * It is also possible to create optional segments by using parentheses in
 * the URI definition:
 *
 * // This is the standard default route, and no keys are required
 * Pelican_Route::default('(<controller>(/<action>(/<id>)))');
 *
 * // This route only requires the :file key
 * Pelican_Route::factory('(<path>/)<file>(<format>)', array('path' => '.*', 'format' => '\.\w+'));
 *
 * Routes also provide a way to generate URIs (called "reverse routing"), which
 * makes them an extremely powerful and flexible way to generate internal links.
 *
 */
class Route
{
    const REGEX_KEY = '<([a-zA-Z0-9_]++)>';

    const REGEX_SEGMENT = '[^/.,;?]++';

    const REGEX_ESCAPE = '[.\\+*?[^\\]${}=!|]';

    /**
     * Route URI string
     *
     * @access protected
     */
    protected $uri = '';

    /**
     * Regular expressions for route keys
     *
     * @access protected
     */
    protected $regex = array();

    /**
     * Default values for route keys
     *
     * @access protected
     */
    protected $defaults = array(
        'controller' => 'index',
        'action'     => 'index'
    );

    /**
     * __DESC__
     *
     * @access protected
     * @var array
     */
    protected $requestParams = array();

    /**
     * Compiled regex cache
     *
     * @access protected
     */
    protected $compiledRegex;

    /**
     * Route classname
     * @var string
     */
    protected $className;


    /**
     * Creates a new route. Sets the URI and regular expressions for keys.
     *
     * @param string $uri (option) route URI pattern
     * @param Array $regex (option) key patterns
     * @param string $className
     */
    public function __construct($uri = null, array $regex = null, $className = null)
    {
        $this->className = $className;

        if ($uri === null) {
            return;
        }

        if (!empty($regex)) {
            $this->regex = $regex;
        }


        // Store the URI that this route will match

        $this->uri = $uri;
        // Store the compiled regex locally
        $this->compiledRegex = $this->_compile();
    }

    /**
     * Provides default values for keys when they are not present. The default
     * action will always be "index" unless it is overloaded here.
     *
     * $route->defaults(array('controller' => 'welcome', 'action' => 'index'));
     *
     * @param Array $defaults (option) key values
     * @return $this
     */
    public function defaults(array $defaults = null)
    {
        $this->defaults = $defaults;

        return $this;
    }

    /**
     * @param array $params
     *
     * @return \Pelican_Route
     */
    public function pushRequestParams(array $params = null)
    {
        $this->requestParams = $params;

        return $this;
    }

    /**
     * Tests if the route matches a given URI. A successful match will return
     * all of the routed parameters as an array. A failed match will return
     * boolean FALSE.
     *
     * // This route will only match if the <controller>, <action>, and <id> exist
     * $params = self::factory('<controller>/<action>/<id>', array('id' => '\d+'))
     * ->matches('users/edit/10');
     * // The parameters are now: controller = users, action = edit, id = 10
     *
     * This method should almost always be used within an if/else block:
     *
     * if ($params = $route->matches($uri))
     * {
     * // Parse the parameters
     * }
     *
     * @param string $uri URI to match
     * @return FALSE
     */
    public function matches($uri)
    {
        if (!preg_match($this->compiledRegex, $uri, $matches)) {
            return false;
        }

        $params = array();
        foreach ($matches as $key => $value) {
            if (is_int($key)) {
                // Skip all unnamed keys
                continue;
            }

            /** gestion du directory : pour le cas 'absolute' */
            if ($key == 'absolute') {

                /** cas particulier de library */
                $params['root'] = 'application/' . $value . 's/' . $matches['name'];
            }

            /** gestion des sous-repertoires : uniquement si controller contient des _ */
            if ($key == 'controller' && substr_count($value, '_')) {
                $temp                = explode('_', $value);
                $value               = array_pop($temp);
                $params['directory'] = implode('/', $temp);
            }
            // Set the value for all matched keys
            $params[$key] = $value;
        }

        foreach ($this->defaults as $key => $value) {
            if (!isset($params[$key]) or $params[$key] === '') {
                // Set default values for any key that was not matched
                $params[$key] = $value;
            }
        }

        return $params;
    }

    /**
     * Generates a URI for the current route based on the parameters given.
     *
     * @param array $params (option) URI parameters
     * @return  string
     */
    public function uri(array $params = null)
    {
        if ($params === null) {
            // Use the default parameters
            $params = $this->defaults;
        } else {
            // Add the default parameters
            $params += $this->defaults;
        }

        // Start with the routed URI
        $uri = $this->uri;

        if (strpos($uri, '<') === false and strpos($uri, '(') === false) {
            // This is a static route, no need to replace anything
            return $uri;
        }

        while (preg_match('#\([^()]++\)#', $uri, $match)) {
            // Search for the matched value


            $search = $match[0];

            // Remove the parenthesis from the match as the replace
            $replace = substr($match[0], 1, -1);

            while (preg_match('#' . self::REGEX_KEY . '#', $replace, $match)) {
                list ($key, $param) = $match;

                if (!empty($params[$param])) {
                    // Replace the key with the parameter value
                    $replace = str_replace($key, $params[$param], $replace);
                } else {
                    // This group has missing parameters
                    $replace = '';
                    break;
                }
            }

            // Replace the group in the URI
            $uri = str_replace($search, $replace, $uri);
        }

        while (preg_match('#' . self::REGEX_KEY . '#', $uri, $match)) {
            list ($key, $param) = $match;

            if (empty($params[$param])) {
                // Ungrouped parameters are required
                throw new \Exception(
                    sprintf('Required route parameter not passed: %s', $param)
                );
            }

            $uri = str_replace($key, $params[$param], $uri);
        }

        // Trim all extra slashes from the URI
        $uri = preg_replace('#//+#', '/', rtrim($uri, '/'));

        return $uri;
    }

    /**
     * Returns the compiled regular expression for the route. This translates
     * keys and optional groups to a proper PCRE regular expression.
     *
     * @access  protected
     * @return  string
     */
    protected function _compile()
    {
        // The URI should be considered literal except for keys and optional parts
        // Escape everything preg_quote would escape except for : ( ) < >
        $regex = preg_replace('#' . self::REGEX_ESCAPE . '#', '\\\\$0', $this->uri);

        if (strpos($regex, '(') !== false) {
            // Make optional parts of the URI non-capturing and optional
            $regex = str_replace(
                array(
                    '(',
                    ')'
                ),
                array(
                    '(?:',
                    ')?'
                ),
                $regex
            );
        }

        // Insert default regex for keys
        $regex = str_replace(
            array(
                '<',
                '>'
            ),
            array(
                '(?P<',
                '>' . self::REGEX_SEGMENT . ')'
            ),
            $regex
        );

        if (!empty($this->regex)) {
            $search = $replace = array();
            foreach ($this->regex as $key => $value) {
                $search[]  = "<$key>" . self::REGEX_SEGMENT;
                $replace[] = "<$key>$value";
            }

            // Replace the default regex with the user-specified regex
            $regex = str_replace($search, $replace, $regex);
        }

        return '#^' . $regex . '$#';
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * @param string $className
     * @return $this
     */
    public function setClassName($className)
    {
        $this->className = $className;

        return $this;
    }
}
