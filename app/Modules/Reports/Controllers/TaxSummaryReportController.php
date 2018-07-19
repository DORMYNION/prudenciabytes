<?php

/**

 *
 
 


 
 *

 */

namespace FI\Modules\Reports\Controllers;

use FI\Http\Controllers\Controller;
use FI\Modules\Reports\Reports\TaxSummaryReport;
use FI\Modules\Reports\Requests\DateRangeRequest;
use FI\Support\PDF\PDFFactory;

class TaxSummaryReportController extends Controller
{
    private $report;

    public function __construct(TaxSummaryReport $report)
    {
        $this->report = $report;
    }

    public function index()
    {
        return view('reports.options.tax_summary');
    }

    public function validateOptions(DateRangeRequest $request)
    {

    }

    public function html()
    {
        $results = $this->report->getResults(
            request('from_date'),
            request('to_date'),
            request('company_profile_id'),
            request('exclude_unpaid_loans')
        );

        return view('reports.output.tax_summary')
            ->with('results', $results);
    }

    public function pdf()
    {
        $pdf = PDFFactory::create();

        $results = $this->report->getResults(
            request('from_date'),
            request('to_date'),
            request('company_profile_id'),
            request('exclude_unpaid_loans')
        );

        $html = view('reports.output.tax_summary')
            ->with('results', $results)->render();

        $pdf->download($html, trans('fi.tax_summary') . '.pdf');
    }
}