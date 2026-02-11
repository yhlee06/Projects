package com.mycompany.gui;

import javax.swing.DefaultCellEditor;
import javax.swing.JComboBox;
import javax.swing.JPanel;
import javax.swing.JScrollPane;
import javax.swing.JTable;
import javax.swing.table.DefaultTableModel;

public class Table_Frame extends JPanel {
	public JTable table;
	public DefaultTableModel model;

	public Table_Frame(int width, int height, int x, int y) {
		setLayout(null);
		setBounds(x, y, width, height);

		table = new JTable();
		JScrollPane scroll = new JScrollPane(table);
		scroll.setBounds(0, 0, width, height);
		add(scroll);
	}

	public void refresh_data(String[] column, String[][] data) {
		model = new DefaultTableModel(data, column) {
			@Override
			public boolean isCellEditable(int row, int column) {
				return column != 0;
			}
		};
		table.setModel(model);
	}

	public void column_width(int[] widths) {
		for (int i = 0; i < widths.length; i++) {
			table.getColumnModel().getColumn(i).setPreferredWidth(widths[i]);
			table.getColumnModel().getColumn(i).setMinWidth(widths[i]);
			table.getColumnModel().getColumn(i).setMaxWidth(widths[i]);
		}
	}

	public void lock_data() {
		int columns = table.getColumnCount();
		int rows = table.getRowCount();
		String[][] data = new String[rows][columns];

		for (int row = 0; row < rows; row++) {
			for (int column = 0; column < columns; column++) {
				data[row][column] = String.valueOf(table.getValueAt(row, column));
			}
		}

		String[] column_name = new String[columns];
		for (int column = 0; column < columns; column++) {
			column_name[column] = table.getColumnName(column);
		}

		model = new DefaultTableModel(data, column_name) {
			@Override
			public boolean isCellEditable(int row, int column) {
				return false;
			}
		};

		table.setModel(model);
	}

	public void dropbox(int column, String[] options) {
		JComboBox<String> drop = new JComboBox<>(options);
		table.getColumnModel().getColumn(column).setCellEditor(new DefaultCellEditor(drop));
	}

	public String[][] get_data(int[] column_order) {
		if (table.isEditing()) {
			table.getCellEditor().stopCellEditing();
		}
		int count_row = model.getRowCount();
		int count_column = column_order.length;
		String[][] table_data = new String[count_row][count_column];
		int column_index;

		for (int row = 0; row < count_row; row++) {
			column_index = 0;
			for (int column : column_order) {
				table_data[row][column_index++] = String.valueOf(table.getValueAt(row, column));
			}
		}
		return table_data;
	}

    public int getSelectedRow() {
        return table.getSelectedRow();
    }

    public Object getValueAt(int row, int column) {
        return table.getValueAt(row, column);
}


}
