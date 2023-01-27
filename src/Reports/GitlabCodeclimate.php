<?php
/**
 * GitLab Code Climate report for PHP_CodeSniffer.
 *
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @copyright 2006-2015 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   https://github.com/squizlabs/PHP_CodeSniffer/blob/master/licence.txt BSD Licence
 */

namespace PHP_CodeSniffer\Reports;

use PHP_CodeSniffer\Files\File;
use stdClass;

class GitlabCodeclimate implements Report
{

    private static $severityMap = [
        1 => 'info',
        2 => 'minor',
        3 => 'major',
        4 => 'critical',
        5 => 'blocker',
    ];


    /**
     * @inheritDoc
     */
    public function generateFileReport($report, File $phpcsFile, $showSources=false, $width=80)
    {
        if ((int) $report['errors'] === 0) {
            return;
        }

        $messages = '';
        foreach ($report['messages'] as $line => $lineErrors) {
            foreach ($lineErrors as $column => $colErrors) {
                foreach ($colErrors as $error) {
                    $messageObject = new stdClass();
                    $messageObject->description     = $error['message'];
                    $messageObject->severity        = self::$severityMap[$error['severity']];
                    $messageObject->location        = new stdClass();
                    $messageObject->location->path  = $report['filename'];
                    $messageObject->location->begin = new stdClass();
                    $messageObject->location->begin->line   = $line;
                    $messageObject->location->begin->column = $column;

                    $messages .= json_encode($messageObject).',';
                }
            }
        }

        echo rtrim($messages, ',');

    }//end generateFileReport()


    /**
     * @inheritDoc
     */
    public function generate(
        $cachedData,
        $totalFiles,
        $totalErrors,
        $totalWarnings,
        $totalFixable,
        $showSources=false,
        $width=80,
        $interactive=false,
        $toScreen=true
    ) {
        echo '[';
        echo rtrim($cachedData, ',');
        echo ']';

    }//end generate()


}//end class
