package com.mycompany.tutor;

import java.util.ArrayList;
import java.util.List;

import com.mycompany.edit.Read;

public class Display_Class_Info {
    private static Read read_file = new Read();
    
    public static String[][] class_data(Tutor tutor) {
        String[] class_data = read_file.read("Advance_Tuition_Centre/src/main/java/com/mycompany/data/class_information.txt");
        String[] assigned_tutor_lines = read_file.read("Advance_Tuition_Centre/src/main/java/com/mycompany/data/tutor_assignment.txt");
        
        /* Get Class ID for tutor */
        List<String> assigned_class = new ArrayList<>();
        for (String assignment_line : assigned_tutor_lines) {
            String[] assign_info = assignment_line.split(";");

           /* Check if there is enough parts */
            if (assign_info.length > 2 && assign_info[1].equals(tutor.get("id"))) { 
                assigned_class.add(assign_info[2]); /* classID */

            }
        }
        
        List<String[]> table_rows = new ArrayList<>();
        for (String class_info_line : class_data) {
            String[] data = class_info_line.split(";");
            
            /* Check if there is enough parts */
            if (data.length >= 7) { 
                String classId = data[0];
                
                if (assigned_class.contains(classId)) {
                    String[] row = new String[6];
                    row[0] = data[0]; /* Class ID */
                    row[1] = data[2]; /* Subject Name */
                    row[2] = data[3]; /* Form */
                    row[3] = data[4]; /* Price */
                    row[4] = data[5]; /* Status */
                    row[5] = data[6]; /* Date Created */
                    table_rows.add(row);
                }
            }
        }
        return table_rows.toArray(new String[0][6]);
    }
    
    public static String[] column_title() {
        return new String[]{"Class ID", "Subject Name", "Form", "Price", "Status", "Date Created"};
    }
    
    public static int[] column_width() {
        return new int[]{80, 200, 60, 80, 80, 100};
    }
}
