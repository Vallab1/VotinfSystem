<?php 
    $election_id = $_GET['viewResult'];
?>
<div class="row my-3">
    <div class="col-12">
        <h3> Election Results </h3>
        <?php 
            $fetchingActiveElections = mysqli_query($db, "SELECT * FROM elections WHERE id = '". $election_id ."'") or die(mysqli_error($db));
            $totalActiveElections = mysqli_num_rows($fetchingActiveElections);
            if($totalActiveElections > 0) 
            {
                while($data = mysqli_fetch_assoc($fetchingActiveElections))
                {
                    $election_id = $data['id'];
                    $election_topic = $data['election_topic'];    
            ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th colspan="4" class="bg-green text-white"><h5> ELECTION TOPIC: <?php echo strtoupper($election_topic); ?></h5></th>
                            </tr>
                            <tr>
                                <th> Photo </th>
                                <th> Candidate Details </th>
                                <th> Votes </th>
                                <!-- <th> Action </th> -->
                            </tr>
                        </thead>
                        <tbody>
                        <?php 
                            $fetchingCandidates = mysqli_query($db, "SELECT * FROM candidate_details WHERE election_id = '". $election_id ."'") or die(mysqli_error($db));
                            
                            $maxVotes = 0; // Initialize maxVotes variable
                            $winnerCandidate = ''; // Initialize winner candidate variable
                            
                            while($candidateData = mysqli_fetch_assoc($fetchingCandidates))
                            {
                                $candidate_id = $candidateData['id'];
                                $candidate_photo = $candidateData['candidate_photo'];
                                // Fetching Candidate Votes 
                                $fetchingVotes = mysqli_query($db, "SELECT * FROM votings WHERE candidate_id = '". $candidate_id . "'") or die(mysqli_error($db));
                                $totalVotes = mysqli_num_rows($fetchingVotes);
                                
                                // Check if current candidate has more votes than the current maxVotes
                                if ($totalVotes > $maxVotes) {
                                    $maxVotes = $totalVotes;
                                    $winnerCandidate = $candidateData; // Update winner candidate data
                                }
                        ?>
                                <tr>
                                    <td> <img src="<?php echo $candidate_photo; ?>" class="candidate_photo"> </td>
                                    <td><?php echo "<b>" . $candidateData['candidate_name'] . "</b><br />" . $candidateData['candidate_details']; ?></td>
                                    <td><?php echo $totalVotes; ?></td>
                                </tr>
                        <?php
                            }
                        ?>
                        </tbody>
                    </table>
                    
                    <?php 
                        // Display winner information
                        if (!empty($winnerCandidate)) {
                            echo "<h4>The winner is: " . $winnerCandidate['candidate_name'] . " with " . $maxVotes . " votes.</h4>";
                        } else {
                            echo "<h4>No winner found.</h4>";
                        }
                    ?>
            <?php 
                }
            } else {
                echo "No any active election.";
            }
        ?>
    </div>
</div>

            <hr>
            <h3>Voting Details</h3>
            <?php 
                $fetchingVoteDetails = mysqli_query($db, "SELECT * FROM votings WHERE election_id = '". $election_id ."'");
                $number_of_votes = mysqli_num_rows($fetchingVoteDetails);
                if($number_of_votes > 0)
                {
                    $sno = 1;
            ?>
                    <table class="table">
                        <tr>
                            <th>S.No</th>
                            <th>Voter Id</th>
                            <th>Contact No</th>
                            <th>Voted To</th>
                            <th>Date </th>
                            <th>Time</th>
                        </tr>
            <?php
                    while($data = mysqli_fetch_assoc($fetchingVoteDetails))
                        {
                            $voters_id = $data['voters_id'];
                            $candidate_id = $data['candidate_id'];
                            $fetchingvoter_number = mysqli_query($db, "SELECT * FROM users WHERE id = '". $voters_id ."'") or die(mysqli_error($db));
                            $isDataAvailable = mysqli_num_rows($fetchingvoter_number);
                            $userData = mysqli_fetch_assoc($fetchingvoter_number);
                            if($isDataAvailable > 0)
                            {
                                $voter_number = $userData['voter_number'];
                                $contact_no = $userData['contact_no'];
                            }else {
                                $voter_number = "No_Data";
                                $contact_no = "No_Data";
                            }
                            $fetchingCandidateName = mysqli_query($db, "SELECT * FROM candidate_details WHERE id = '". $candidate_id ."'") or die(mysqli_error($db));
                            $isDataAvailable = mysqli_num_rows($fetchingCandidateName);
                            if($isDataAvailable > 0)
                            {
                                $candidateData = mysqli_fetch_assoc($fetchingCandidateName);
                                $candidate_name = $candidateData['candidate_name'];
                            }else {
                                $candidate_name = "No_Data";
                            }
                ?>
                            <tr>
                                <td><?php echo $sno++; ?></td>
                                <td><?php echo $voter_number; ?></td>
                                <td><?php echo $contact_no; ?></td>
                                <td><?php echo $candidate_name; ?></td>
                                <td><?php echo $data['vote_date']; ?></td>
                                <td><?php echo $data['vote_time']; ?></td>
                            </tr>
                <?php
                        }
                        echo "</table>";
                    }else {
                        echo "No any vote detail is available!";
                    }
                ?>
            </table>
            
        </div>
    </div>


