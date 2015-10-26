<?php $keys=array_keys($user[0]); ?>
    <table class="table table-striped">
            <thead>
            <tr>
				<?php foreach($keys as $val) {
                echo "<th>$val</th>";
			    } ?>
            </tr>
        </thead>
        <tbody>
		<?php
		foreach($user as $key=>$val){
		    echo "<tr>";
			foreach($val as $k=>$v){
				echo "<td>$v</td>";
		    }
		    echo "</tr>";
		}
		?>
        </tbody>
    </table>