<?php
      
function the_acf_table() {

  $table = get_field( 'table' );

  if ( ! empty ( $table ) ) {

      echo '<table class="acfTable" border="0">';

          if ( ! empty( $table['caption'] ) ) {

              echo '<caption>' . $table['caption'] . '</caption>';
          }

          if ( ! empty( $table['header'] ) ) {

              echo '<thead class="acfTable__head">';

                  echo '<tr>';

                      foreach ( $table['header'] as $th ) {

                          echo '<th>';
                              echo $th['c'];
                          echo '</th>';
                      }

                  echo '</tr>';

              echo '</thead>';
          }

          echo '<tbody>';

              foreach ( $table['body'] as $tr ) {

                  echo '<tr class="acfTable__row">';

                      foreach ( $tr as $td ) {

                          echo '<td class="acfTable__item">';
                              echo $td['c'];
                          echo '</td>';
                      }

                  echo '</tr>';
              }

          echo '</tbody>';

      echo '</table>';
  }

}