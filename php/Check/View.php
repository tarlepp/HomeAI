<?php
/**
 * \php\Check\View.php
 *
 * @package     Core
 * @subpackage  Check
 * @category    View
 */
namespace HomeAI\Check;

/**
 * Generic module view class. All module view classes must extend this base class.
 *
 * @package     Core
 * @subpackage  Check
 * @category    View
 *
 * @date        $Date$
 * @author      $Author$
 * @revision    $Rev$
 */
class View implements Interfaces\View
{
    /**
     * Current running mode, CLI or HTTP
     *
     * @var int
     */
    protected $mode;

    const MODE_CLI = 0;
    const MODE_HTTP = 1;

    /**
     * Construction of the class.
     *
     * @return  \HomeAI\Check\View
     */
    public function __construct()
    {
        $this->mode = (\PHP_SAPI === 'cli') ? self::MODE_CLI : self::MODE_HTTP;
    }

    /**
     * Make of header.
     *
     * @return  void
     */
    public function makeHeader()
    {
        if ($this->mode === self::MODE_CLI) {
            $header = str_repeat('=', 76);
            $header .= "\nEnvironment checks for HomeAI:";
            $header .= "\n". str_repeat('-', 76);

            $this->makeCliString($header);
        } else {
            $this->makeHtmlHeader();
        }
    }

    /**
     * Make of footer.
     *
     * @return  void
     */
    public function makeFooter()
    {
        if ($this->mode === self::MODE_CLI) {
            $footer = "All done\n";
            $footer .= str_repeat('=', 76);

            $this->makeCliString($footer);
        } else {
            $this->makeHtmlFooter();
        }
    }

    /**
     * Make of section header.
     *
     * @param   string  $section
     *
     * @return  void
     */
    public function makeSectionHeader($section)
    {
        if ($this->mode === self::MODE_CLI) {
            $this->makeCliString($section);
        } else {
            ?>
            <table class="table table-bordered table-hover">
                <thead>
                    <tr class="section">
                        <th><?= $section ?></th>
                        <th class="span1">Status</th>
                    </tr>
                </thead>
                <tbody>
            <?php
        }
    }

    /**
     * Make of section footer.
     *
     * @param   string  $section
     *
     * @return  void
     */
    public function makeSectionFooter($section)
    {
        if ($this->mode === self::MODE_CLI) {
            $footer = str_repeat('-', 76);

            $this->makeCliString($footer);
        } else {
            ?>
                </tbody>
            </table>
            <?php
        }
    }

    /**
     * Make check output
     *
     * @param   array   $check  Check method data
     * @param   bool    $result Check result
     * @param   string  $error  Error information
     *
     * @return  void
     */
    public function makeCheck(array $check, $result, $error = '')
    {
        if ($result) {
            $resultString = 'OK';
            $resultClass = 'success';
        } else {
            $resultString = 'ERR';
            $resultClass = 'error';
        }

        if ($this->mode === self::MODE_CLI) {
            $this->makeCliString(" ". $check['title'], $resultString, $resultClass);

            if (!$result) {
                $this->makeCliString(" \033[31mError: ". $error ."\033[37m");
            }
        } else {
            ?>
            <tr class="<?= $resultClass ?>">
                <td>
                    <?php
                    echo $check['title'];

                    if ($check['description']) {
                        printf('<i class="icon-comment" title="%s"></i>',
                            $check['description']
                        );
                    }

                    if ($check['link']) {
                        printf(
                            '<a href="%s" title="%s" target="_blank"><i class="icon-globe"></i></a>',
                            $check['link'],
                            $check['link']
                        );
                    }

                    if (!$result) {
                        echo "<pre>". $error ."</pre>";
                    }
                    ?>
                </td>
                <td class="span1"><?= $resultString ?></td>
            </tr>
            <?php
        }
    }

    /**
     * Method print specified strings to CLI output.
     *
     * @param   string  $stringLeft
     * @param   string  $stringRight
     * @param   string  $resultClass
     *
     * @return  void
     */
    private function makeCliString($stringLeft, $stringRight = '', $resultClass = 'success')
    {
        echo $stringLeft;

        if (!empty($stringRight)) {
            $spaces = 76 - (mb_strlen($stringLeft) + mb_strlen($stringRight) + 3);

            for ($i = 0; $i <= $spaces; $i++) {
                echo " ";
            }

            if ($resultClass === 'success') {
                echo "\033[32m";
            } else {
                echo "\033[31m";
            }

            echo "[". $stringRight ."]";
            echo "\033[37m";
        }

        echo "\n";
    }

    /**
     * Method prints out HTML header information.
     *
     * @return  void
     */
    private function makeHtmlHeader()
    {
        ?>
        <!DOCTYPE html>
        <html lang="en" xmlns="http://www.w3.org/1999/xhtml">
        <head>
            <title>HomeAI - Environment checks</title>

            <meta name="author" content="Tarmo Leppänen"/>
            <meta name="description" content="HomeAI see https://github.com/tarlepp/HomeAI"/>
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">

            <link rel="icon" href="images/layout/favicon.ico" type="image/x-icon"/>
            <link rel="shortcut icon" href="images/layout/favicon.ico" type="image/x-icon"/>
            <link href='http://fonts.googleapis.com/css?family=Cuprum:400,700,400italic,700italic' rel='stylesheet' type='text/css'>

            <link href="css/js/bootstrap/bootstrap.css" rel="stylesheet" type="text/css" media="screen, projection" />
            <link href="css/js/bootstrap/bootstrap-responsive.css" rel="stylesheet" type="text/css" media="screen, projection" />
            <link href="css/homeai.css" rel="stylesheet" type="text/css" media="screen, projection" />
            <link href="css/responsive.css" rel="stylesheet" type="text/css" media="screen, projection" />
            <link href="css/print.css" rel="stylesheet" type="text/css" media="print" />

            <style type="text/css">
                .wrapper > .container {
                    padding-top: 62px;
                }

                .table th {
                    font-size: 18px;
                    line-height: 26px;
                    color: #999999;
                    background-color: #1B1B1B;
                }

                .table-bordered th {
                    padding: 4px 5px 0 10px;
                }

                .table-bordered,
                .table-bordered td,
                .table-bordered th {
                    border-collapse: collapse;
                    border: none;
                }

                .table th.span1 {
                    font-size: 14px;
                }

                .table td.span1 {
                    text-align: center;
                }

                [class^="icon-"],
                [class*=" icon-"] {
                    margin-left: 5px;
                }
            </style>

        </head>
        <body>
            <div class="header">
                <div class="navbar navbar-inverse navbar-fixed-top">
                    <div class="navbar-inner">
                        <div class="container">
                            <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                            <a class="brand" href="homeai.php">HomeAI</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="wrapper">
                <div class="container">

        <?php
    }

    /**
     * Method prints out HTML footer information.
     *
     * @return  void
     */
    private function makeHtmlFooter()
    {
        ?>
                    </table>
                </div>
            </div>
        </body>
        </html>
        <?php
    }
}
