package com.mycompany.tutor;

import java.awt.event.WindowAdapter;
import java.awt.event.WindowEvent;
import java.util.ArrayList;
import java.util.List;

import javax.swing.JFrame;

import com.mycompany.edit.Delete;
import com.mycompany.edit.Read;
import com.mycompany.edit.Update;
import com.mycompany.gui.Base_Frame;
import com.mycompany.gui.Button_Frame;
import com.mycompany.gui.Label_Frame;
import com.mycompany.gui.Message_Frame;
import com.mycompany.gui.Table_Frame;

public class Class_Info {
    static Base_Frame base;
    static Table_Frame class_info_table;
    static Read read_file = new Read();
    static Tutor current_tutor;
    static Update update_feature = new Update();

    public static void Class_Table(Tutor tutor) {
        current_tutor = tutor;

        /* Base Frame, Table & Label */
        base = new Base_Frame("Class Information", 910, 500);
        class_info_table = new Table_Frame(600, 350, 55, 80);
        Label_Frame title = new Label_Frame("Class Information", 30, 30, 280, 35);
        title.font(true, 25);

        /* Add, Delete & Update Buttons */
        Button_Frame add_button = new Button_Frame("Add", 150, 34, 700, 80, e -> add_class());
        Button_Frame delete_button = new Button_Frame("Delete", 150, 34, 700, 130, e -> delete_class());
        Button_Frame update_button = new Button_Frame("Update", 150, 34, 700, 180, e -> update_class());
        base.add_widget(title);
        base.add_widget(class_info_table);
        base.add_widget(add_button);
        base.add_widget(update_button);
        base.add_widget(delete_button);
        
        refreshClassTable(); /* Initial display data */
        
        base.setVisible(true);
        base.setDefaultCloseOperation(JFrame.DO_NOTHING_ON_CLOSE);
        base.addWindowListener(new WindowAdapter() {
            public void windowClosing(WindowEvent e) {
                Tutor_Menu.menu();
                base.dispose();
            }
        });
    }

    public static void refreshClassTable() {
        
        /* Check if current_tutor is null */
        if (current_tutor == null) {
            System.err.println("Error: current_tutor is null in refreshClassTable. Cannot refresh table.");
            return;
        }
        
        /* Display Data */
        String[] column_headers = Display_Class_Info.column_title();
        String[][] table_data = Display_Class_Info.class_data(current_tutor);
        int[] column_width = Display_Class_Info.column_width();
        class_info_table.refresh_data(column_headers, table_data);
        class_info_table.lock_data();
        class_info_table.column_width(column_width);
    }

    private static void add_class() {
        Add_Class.Add_Class(current_tutor);
    }

    private static void update_class() {
        int selectedRow = class_info_table.getSelectedRow();
        if (selectedRow != -1) {
            int columnCount = class_info_table.model.getColumnCount();
            String[] classInfo = new String[columnCount];
            for (int i = 0; i < columnCount; i++) {
                classInfo[i] = (String) class_info_table.getValueAt(selectedRow, i);
            }
            Update_Class_Info.Update_Class_Window(current_tutor, classInfo);
        } else {
            Message_Frame.message_frame("Selection Error", "Please select a class first.");
        }
    }

    private static void delete_class() {
        int selectedRow = class_info_table.getSelectedRow();
        if (selectedRow != -1) {
            String classIdToDelete = (String) class_info_table.getValueAt(selectedRow, 0);
            Delete delete = new Delete();
            String totalFilePath = "Advance_Tuition_Centre/src/main/java/com/mycompany/data/total.txt";
            String classInfoFilePath = "Advance_Tuition_Centre/src/main/java/com/mycompany/data/class_information.txt";
            String tutorAssignFilePath = "Advance_Tuition_Centre/src/main/java/com/mycompany/data/tutor_assignment.txt";
            try {

                /* Get totals for class and assignment */
                String[] total_list = read_file.read(totalFilePath);
                int current_class_total = 0;
                int current_assignment_total = 0;
                for (String line : total_list) {
                    String[] parts = line.split(";");
                    if (parts.length == 2) {
                        if (parts[0].equals("class")) {
                            current_class_total = Integer.parseInt(parts[1]);
                        } else if (parts[0].equals("assignment")) {
                            current_assignment_total = Integer.parseInt(parts[1]);
                        }
                    }
                }

                /* Delete class from class_information.txt */
                delete.delete_data(classInfoFilePath, classIdToDelete);

                /* Delete associated assignments from tutor_assignment.txt and count deleted */
                String[] allAssignments = read_file.read(tutorAssignFilePath);
                List<String> assignmentsToKeep = new ArrayList<>();
                int deleted_assignment_count = 0;
                for (String assignmentLine : allAssignments) {
                    String[] parts = assignmentLine.split(";");
                    if (parts.length >= 3 && !parts[2].equals(classIdToDelete)) {
                        assignmentsToKeep.add(assignmentLine);
                    } else if (parts.length >= 3 && parts[2].equals(classIdToDelete)) {
                        deleted_assignment_count++;
                    }
                }

                /* Overwrite tutor_assignment.txt with remaining assignments */
                String[][] assignmentsToKeepArray = new String[assignmentsToKeep.size()][];
                for (int i = 0; i < assignmentsToKeep.size(); i++) {
                    assignmentsToKeepArray[i] = assignmentsToKeep.get(i).split(";");
                }
                update_feature.clear_and_update(tutorAssignFilePath, assignmentsToKeepArray);

                /* Update total.txt with new class and assignment counts */
                int new_class_total = current_class_total > 0 ? current_class_total - 1 : 0;
                int new_assignment_total = current_assignment_total - deleted_assignment_count;
                if (new_assignment_total < 0) new_assignment_total = 0; 
                update_feature.update_file(totalFilePath, "class", 1, String.valueOf(new_class_total));
                update_feature.update_file(totalFilePath, "assignment", 1, String.valueOf(new_assignment_total));

                /* Refresh table and show success message */
                refreshClassTable();

                Message_Frame.message_frame("Deletion Success", "Class " + classIdToDelete + " and its assignments have been deleted. Totals updated.");
            } catch (Exception e) {
                Message_Frame.message_frame("Deletion Error", "An error occurred while deleting the class: " + e.getMessage());
                e.printStackTrace();
            }
        } else {
            Message_Frame.message_frame("Selection Error", "No row selected for deletion.");
        }
    }
}
