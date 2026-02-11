package com.mycompany.student;

import java.awt.event.WindowAdapter;
import java.awt.event.WindowEvent;
import java.util.ArrayList;
import java.util.List;

import javax.swing.JFrame;

import com.mycompany.edit.Delete;
import com.mycompany.edit.Read;
import com.mycompany.gui.*;

public class View_Request {
	static Base_Frame base;
    static Table_Frame table;
    static Read file = new Read();
    
    public static void View_Request(Student user) {
    	base = new Base_Frame("Show Request", 1200, 500);
        
        Label_Frame title = new Label_Frame("Request", 30, 30, 280, 35);
        title.font(true, 25);
        
		Button_Frame delete_button = new Button_Frame("DELETE", 164, 35, 980, 30, e -> select("delete", user));

        String[] column = {"Request ID", "student ID", "Massage", "Date", "Time", "Status"};
        String[] data_file = file.read("Advance_Tuition_Centre/src/main/java/com/mycompany/data/request.txt");
		List<String[]> filteredData = new ArrayList<>();

		for (String line : data_file) {
			String[] data = line.split(";");
			if (data[1].equals(user.get("id"))) {
				filteredData.add(data);
			}
		}
		
		String[][] table_data = new String[filteredData.size()][6];
		for (int i = 0; i < filteredData.size(); i++) {
			String[] row = filteredData.get(i);
			System.arraycopy(row, 0, table_data[i], 0, 6);
		}

        table = new Table_Frame(1090, 350, 55, 80);
        table.refresh_data(column, table_data);
        int[] widths = {70, 70, 660, 100, 100, 90};
        table.column_width(widths);
        
        base.add_widget(title);
        base.add_widget(table);
        base.add_widget(delete_button);
        
        base.setVisible(true);
        base.setDefaultCloseOperation(JFrame.DO_NOTHING_ON_CLOSE);
        base.addWindowListener(new WindowAdapter() {
            public void windowClosing(WindowEvent e){
                Request_Menu.menu();
                base.dispose();
            }
        });
    }
    
    private static void select(String selection, Student user) {
        switch (selection){
            case "delete":
            	Delete delete = new Delete();
                String request_id = Message_Frame.input_frame("Remove Request", "Please enter Request ID.");
                if (request_id == null || request_id.equals("")){
                    break;
                }
                
                String[] data_file = file.read("Advance_Tuition_Centre/src/main/java/com/mycompany/data/request.txt");
                boolean found = false;
                String target_line = "";
                
                for(String line : data_file) {
					String[] parts = line.split(";");
                	if(parts[0].equals(request_id)){
                		if(!parts[1].equals(user.get("id"))) {
                            Message_Frame.message_frame("Error", "You can only delete your own request.");
                            return;
                		}
                        if (!parts[5].equalsIgnoreCase("pending")) {
                            Message_Frame.message_frame("Cannot Delete", "Only 'pending' requests can be deleted.");
                            return;
                        }
                        target_line = line;
                        found = true;
                        break;
                	}
                }
                
                if (!found) {
                    Message_Frame.message_frame("Not Found", "Request ID not found.");
                    return;
                }

                boolean confirm = Message_Frame.confirm_frame("Confirm Deletion", String.format("Do you really want to delete this request (%s)?", request_id));
                if (confirm == true){
                    delete.delete_data("Advance_Tuition_Centre/src/main/java/com/mycompany/data/request.txt", request_id);
                    Message_Frame.message_frame("Deletion Successful", "Successfully deleted request.");
                }
                break;

        }
        
		base.dispose();
		Request_Menu.menu();
    }
}
