<?php include 'db_connect.php' ?>
<div class="col-md-12">
    <div class="card card-outline card-success">
        <div class="card-header">
            <b>Project Progress</b>
            <div class="card-tools">
                <button class="btn btn-flat btn-sm bg-gradient-success btn-success" id="print">
                    <i class="fa fa-print"></i> Print
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive" id="printable">
                <table class="table m-0 table-bordered">
                    <thead>
                    <th>#</th>
                    <th>Project</th>
                    <th>Task</th>
                    <th>Completed Task</th>
                    <th>Days Remaining</th>
                    <th>Progress</th>
                    <th>Status</th>
                    </thead>
                    <tbody>
                    <?php
                    $i = 1;
                    $stat = array("Pending","Started","On-Progress","On-Hold","Over Due","Done");
                    $where = "";
                    if($_SESSION['login_type'] == 2){
                        $where = " WHERE manager_id = '{$_SESSION['login_id']}' ";
                    } elseif($_SESSION['login_type'] == 3){
                        $where = " WHERE CONCAT('[', REPLACE(user_ids, ',', '],['), ']') LIKE '%[{$_SESSION['login_id']}]%' ";
                    }
                    $qry = $conn->query("SELECT * FROM project_list $where ORDER BY name ASC");
                    while($row = $qry->fetch_assoc()):
                        $tprog = $conn->query("SELECT * FROM task_list WHERE project_id = {$row['id']}")->num_rows;
                        $cprog = $conn->query("SELECT * FROM task_list WHERE project_id = {$row['id']} AND status = 3")->num_rows;
                        $prog = $tprog > 0 ? ($cprog / $tprog) * 100 : 0;
                        $prog = $prog > 0 ? number_format($prog, 2) : $prog;

                        // Calcul des jours restants
                        $endDate = strtotime($row['end_date']);
                        $currentDate = strtotime(date('Y-m-d'));
                        $daysRemaining = ($endDate - $currentDate) / 86400; // Convertir secondes en jours
                        $daysRemaining = $daysRemaining > 0 ? floor($daysRemaining) : 0;

                        // Mise à jour du statut du projet
                        if($row['status'] == 0 && $currentDate >= strtotime($row['start_date'])):
                            if($cprog > 0)
                                $row['status'] = 2; // Started
                            else
                                $row['status'] = 1; // Pending
                        elseif($row['status'] == 0 && $currentDate > $endDate):
                            $row['status'] = 4; // Over Due
                        endif;
                        ?>
                        <tr>
                            <td><?php echo $i++ ?></td>
                            <td>
                                <a><?php echo ucwords($row['name']) ?></a>
                                <br>
                                <small>Due: <?php echo date("Y-m-d", strtotime($row['end_date'])) ?></small>
                            </td>
                            <td class="text-center"><?php echo number_format($tprog) ?></td>
                            <td class="text-center"><?php echo number_format($cprog) ?></td>
                            <td class="text-center"><?php echo $daysRemaining . ' day(s)' ?></td>
                            <td class="project_progress">
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-green" role="progressbar" aria-valuenow="<?php echo $prog ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $prog ?>%">
                                    </div>
                                </div>
                                <small><?php echo $prog ?>% Complete</small>
                            </td>
                            <td class="project-state">
                                <?php
                                $statusClass = [
                                    "Pending" => "badge-secondary",
                                    "Started" => "badge-primary",
                                    "On-Progress" => "badge-info",
                                    "On-Hold" => "badge-warning",
                                    "Over Due" => "badge-danger",
                                    "Done" => "badge-success"
                                ];
                                echo "<span class='badge " . $statusClass[$stat[$row['status']]] . "'>{$stat[$row['status']]}</span>";
                                ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    $('#print').click(function(){
        start_load();
        var _h = $('head').clone();
        var _p = $('#printable').clone();
        var _d = "<p class='text-center'><b>Project Progress Report as of (<?php echo date('F d, Y') ?>)</b></p>";
        _p.prepend(_d);
        _p.prepend(_h);
        var nw = window.open("", "", "width=900,height=600");
        nw.document.write(_p.html());
        nw.document.close();
        nw.print();
        setTimeout(function(){
            nw.close();
            end_load();
        }, 750);
    });
</script>
