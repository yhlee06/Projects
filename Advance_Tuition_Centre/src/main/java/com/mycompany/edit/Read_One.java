package com.mycompany.edit;

public class Read_One {
    public String[] read(String data_id, String file_path){
        Read file = new Read();
        String[] all_data = file.read(file_path);
        String[] selected_data;

        for (int row = 0; row < all_data.length; row++){
            selected_data = all_data[row].split(";");
            if (selected_data[0].equals(data_id)){
                return selected_data;
            }
        }

        return null;
    }
}
