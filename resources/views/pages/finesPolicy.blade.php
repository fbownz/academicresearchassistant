@extends ('layout')

@section('body')
<!-- Main content -->
    <section class="content">
     <div class="box">
         <div class="box-body table-responsive">
            <table class="table table-striped">
                      <thead>
                        <tr>
                          <th>Fine Type</th>
                          <th>Description</th>
                          <th>Order Status</th>
                          <th>Value (% from Order total)</th>
                          <th>Order compensation</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td><h4>Neglect</h4></td>
                          <td>Project uploaded after indicated deadline</td>
                          <td>Cancelled - Late order submission
                          </td>
                          <td>100%</td>
                          <td>No</td>
                        </tr>
                        <tr>
                          <td>
                          </td>
                          <td>
                            Disappearance from the project 
                          </td>
                          <td>Cancelled - Disappeared from the project
                          </td> 
                          <td>100%</td>  
                          <td>No</td>
                        </tr>
                        <tr>
                          <td></td>
                          <td>Close to deadline reassign request</td>
                          <td>Cancelled - Late Reassign request</td>
                          <td>50%</td>
                          <td>No</td>
                        </tr>
                        <tr>
                          <td></td>
                          <td>Violation of Company's policy</td>
                          <td>Cancelled Fraudulent Account*</td>
                          <td>200%</td>
                          <td>No</td>
                        </tr>
                        <tr>
                          <td></td>
                          <td>Failure to complete Multiple choice (time framed)</td>
                          <td>Cancelled - Late reassign request, Reassign request</td>
                          <td>100%</td>
                          <td>No</td>
                        </tr>
                        <tr>
                          <td><h4>Low Quality</h4></td>
                          <td>Requirements full mismatch</td>
                          <td>Cancelled - Low quality product</td>
                          <td>100%</td>
                          <td>No</td>
                        </tr>
                        <tr>
                          <td></td>
                          <td>Requirements partial mismatch</td>
                          <td>Completed</td>
                          <td>40%</td>
                          <td>Yes</td>
                        </tr>
                        <tr>
                          <td></td>
                          <td>Proofread. Minor Errors detected</td>
                          <td>Completed</td>
                          <td>5%</td>
                          <td>Yes</td>
                        </tr>
                        <tr>
                          <td></td>
                          <td>Proofread. Major Errors detected</td>
                          <td>Completed</td>
                          <td>10%</td>
                          <td>Yes</td>
                        </tr>
                        <tr>
                          <td></td>
                          <td>Proofread. Substandard Quality</td>
                          <td>Completed - the order was returned to the writer by the Quality Assurance Dept for further revision</td>
                          <td>15%</td>
                          <td>Yes</td>
                        </tr>
                        <tr>
                          <td></td>
                          <td></td>
                          <td>Completed - the order was returned back for revision by the Quality Assurance Dept for the second time</td>
                          <td>40%</td>
                          <td>Yes</td>
                        </tr>
                        <tr>
                          <td></td>
                          <td></td>
                          <td>Completed - the order has been estimated as 'low quality product' by the Quality Assurance Dept</td>
                          <td>40%</td>
                          <td>Yes</td>
                        </tr>
                        <tr>
                          <td><h4>Plagiarism</h4></td>
                          <td>Plagiarism detected. Paper cannot be used</td>
                          <td>Canceled Plagiarism detected</td>
                          <td>110%</td>
                          <td>No</td>
                        </tr>
                        <tr>
                          <td></td>
                          <td>Plagiarism detected. Paper is partially usable</td>
                          <td>Completed</td>
                          <td>30%+ earnings are shifted by two months</td>
                          <td>Yes</td>
                        </tr>
                        <tr>
                          <td><h4>Late order submission</h4></td>
                          <td>Product deadline violation</td>
                          <td>Completed</td>
                          <td>10%</td>
                          <td>Yes</td>
                        </tr>
                        <tr>
                          <td></td>
                          <td>Product revision deadline violation</td>
                          <td>Completed</td>
                          <td>10%</td>
                          <td>Yes</td>
                        </tr>
                        <tr>
                          <td><h4>Other</h4></td>
                          <td>Violation of 'Report on progress' requirement</td>
                          <td>Preparing/Completed</td>
                          <td>from 10% and up</td>
                          <td>Yes</td>
                        </tr>
                        <tr>
                          <td></td>
                          <td>Deadline recalculation</td>
                          <td>Completed - the order was partially refunded due to deadline extension request</td>
                          <td>from 10% and up</td>
                          <td>Yes</td>
                        </tr>
                        <tr>
                          <td></td>
                          <td>Abuse of communication tools </td>
                          <td>Completed - multiple duplicate requests, follow ups through various communication tools without sound ground</td>
                          <td>from 10% and up</td>
                          <td>Yes</td>
                        </tr>
                      </tbody>
              </table>
         </div>
       </div>

    </section>
  @stop