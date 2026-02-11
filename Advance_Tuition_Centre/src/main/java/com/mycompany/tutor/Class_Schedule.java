package com.mycompany.tutor;

import java.awt.event.WindowAdapter;
import java.awt.event.WindowEvent;

import javax.swing.JFrame;

import com.mycompany.edit.Delete;
import com.mycompany.edit.Read;
import com.mycompany.edit.Update;
import com.mycompany.gui.Base_Frame;
import com.mycompany.gui.Button_Frame;
import com.mycompany.gui.Label_Frame;
import com.mycompany.gui.Message_Frame;
import com.mycompany.gui.Table_Frame;

public class Class_Schedule {
    static Base_Frame base;
    static Table_Frame schedule_table;
    static Read read_file = new Read();
    static Update update_feature = new Update();
    static Tutor current_tutor;

    public static void Schedule_Table(Tutor tutor){
        current_tutor = tutor;

        /* Base Frame, Table & Label */
        base = new Base_Frame("Class Schedule", 1050, 500);
        schedule_table = new Table_Frame(720, 350, 55, 80);
        Label_Frame title = new Label_Frame("Class Schedule", 30, 30, 280,35);
        title.font(true, 25);
        
        /* Add, Delete & Update Buttons */
        Button_Frame add_button = new Button_Frame("Add", 150, 34, 825, 80, e-> add_class());
        Button_Frame delete_button = new Button_Frame("Delete", 150, 34, 825, 130, e-> delete_class());
        Button_Frame update_button = new Button_Frame("Update", 150, 34, 825, 180, e-> update_class());
        
        base.add_widget(title);
        base.add_widget(schedule_table);
        base.add_widget(add_button);
        base.add_widget(update_button);
        base.add_widget(delete_button);
        
        /* Initial display data */
        refreshScheduleTable(); 
        
        base.setVisible(true);
        base.setDefaultCloseOperation(JFrame.DO_NOTHING_ON_CLOSE);
        base.addWindowListener(new WindowAdapter() {
            public void windowClosing(WindowEvent e) {
                Tutor_Menu.menu();
                base.dispose();
            }
        });
    }

    public static void refreshScheduleTable() {

        /* Check if current_tutor is null */
        if (current_tutor == null) {
            System.err.println("Error: current_tutor is null in refreshScheduleTable. Cannot refresh table.");
            return;
        }

        /* Display Data */
        String [] column_headers = Display_Class_Schedule.column_title();
        String [][] schedule_data = Display_Class_Schedule.class_schedule_data(current_tutor);
        int [] column_width = Display_Class_Schedule.column_width();
        schedule_table.refresh_data(column_headers, schedule_data);
        schedule_table.lock_data();
        schedule_table.column_width(column_width);
    }

    private static void add_class() {
        Add_Schedule.Add_Class(current_tutor);
    }

    private static void update_class(){
        int selectedRow = schedule_table.getSelectedRow();
        if (selectedRow != -1) {
            int columnCount = schedule_table.model.getColumnCount();
            String[] scheduleInfo = new String[columnCount];
            for (int i = 0; i < columnCount; i++) {
                scheduleInfo[i] = (String) schedule_table.getValueAt(selectedRow, i);
            }
            Update_Class_Schedule.Update_Schedule_Window(current_tutor, scheduleInfo);
        } else {
            Message_Frame.message_frame("Selection Error", "Please select a schedule first.");
        }
    }

    private static void delete_class() {
        int selectedRow = schedule_table.getSelectedRow();
        if (selectedRow != -1) {
            String scheduleIdToDelete = (String) schedule_table.getValueAt(selectedRow, 0);
            Delete delete = new Delete();
            String totalFilePath = "Advance_Tuition_Centre/src/main/java/com/mycompany/data/total.txt";
            String classScheduleFilePath = "Advance_Tuition_Centre/src/main/java/com/mycompany/data/class_schedule.txt";

            try {
                /* Get total for schedules */
                String[] total_list = read_file.read(totalFilePath);
                int current_schedule_total = 0;

                for (String line : total_list) {
                    String[] parts = line.split(";");
                    if (parts.length == 2 && parts[0].equals("schedule")) {
                        current_schedule_total = Integer.parseInt(parts[1]);
                        break;
                    }
                }

                /* Delete from class_schedule.txt */
                delete.delete_data(classScheduleFilePath, scheduleIdToDelete);

                /* Update Totals */
                int new_schedule_total = current_schedule_total > 0 ? current_schedule_total - 1 : 0;
                update_feature.update_file(totalFilePath, "schedule", 1, String.valueOf(new_schedule_total));

                /* Refresh table and show success message */
                refreshScheduleTable(); 
                Message_Frame.message_frame("Deletion Success", "Schedule " + scheduleIdToDelete + " has been deleted. Total updated.");

            } catch (Exception e) {
                System.err.println("Error caught during schedule deletion: " + e.getMessage());
                Message_Frame.message_frame("Deletion Error", "An error occurred while deleting the schedule: " + e.getMessage());
                e.printStackTrace(); 
            }
        } else {
            Message_Frame.message_frame("Selection Error", "No row selected for deletion.");
        }
    }
}
