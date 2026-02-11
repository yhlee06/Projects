package com.mycompany.edit;

import java.io.*;

public class Delete {
    public void delete_data(String file_path, String delete_id){
        Read read_file = new Read();
        String[] current_data = read_file.read(file_path);
        String[] data;

        try{
            FileWriter write_file = new FileWriter(file_path);
            BufferedWriter write_buffer = new BufferedWriter(write_file);
            
            for (int i = 0; i < current_data.length; i++){
                data = current_data[i].split(";");
                if (!data[0].equals(delete_id)){
                    write_buffer.write(current_data[i]);
                    write_buffer.newLine();
                }
            }
            write_buffer.close();
        }catch(Exception e){
        System.out.println("");
    }
    }
}
