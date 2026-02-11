package com.mycompany.student;

import java.awt.event.WindowAdapter;
import java.awt.event.WindowEvent;
import java.util.ArrayList;
import java.util.List;

import javax.swing.JFrame;
import com.mycompany.edit.*;
import com.mycompany.gui.*;

public class Payment_Proof {
	static Base_Frame base;
	static Table_Frame table;
	static Read file = new Read();

	public static void Payment_Proof(Student user) {
		base = new Base_Frame("Payment Proof", 1010, 500);

		Label_Frame title = new Label_Frame("Payment Proof", 30, 30, 280, 35);
		title.font(true, 25);

		String[] column = { "Payment ID", "student ID", "Massage", "File", "Payment Date", "Payment Time", "Status" };
		String[] data_file = file.read("Advance_Tuition_Centre/src/main/java/com/mycompany/data/payment_proof.txt");
		List<String[]> filteredData = new ArrayList<>();

		for (String line : data_file) {
			String[] data = line.split(";");
			if (data[1].equals(user.get("id"))) {
				filteredData.add(data);
			}
		}
		
		String[][] table_data = new String[filteredData.size()][7];
		for (int i = 0; i < filteredData.size(); i++) {
			String[] row = filteredData.get(i);
			System.arraycopy(row, 0, table_data[i], 0, 7);
		}
		
		table = new Table_Frame(900, 350, 55, 80);
		table.refresh_data(column, table_data);
		int[] widths = { 70, 70, 320, 160, 100, 90, 100 };
		table.column_width(widths);

		base.add_widget(title);
		base.add_widget(table);

		base.setVisible(true);
		base.setDefaultCloseOperation(JFrame.DO_NOTHING_ON_CLOSE);
		base.addWindowListener(new WindowAdapter() {
			public void windowClosing(WindowEvent e) {
				Student_Menu.menu();
				base.dispose();
			}
		});
	}

}
