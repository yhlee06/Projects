package com.mycompany.edit;

import java.io.*;

public class Update {
    FileWriter write_file;
    BufferedWriter write_buffer;
    Read read_file = new Read();

    public void update_file(String file_path, String id, int index, String update_data){
        String[] current_data = read_file.read(file_path);
        String[] data;
        String new_data;

        try{
            write_file = new FileWriter(file_path);
            write_buffer = new BufferedWriter(write_file);

            for (int i = 0; i < current_data.length; i++){
                data = current_data[i].split(";");
                if (data[0].equals(id)){
                    data[index] = update_data;
                    new_data = String.join(";", data);
                    write_buffer.write(new_data);
                    write_buffer.newLine();
                }else{
                    write_buffer.write(current_data[i]);
                    write_buffer.newLine();
                }
            }
            write_buffer.close();
        } catch(Exception e){
            System.out.println(e);
        }
    }

    public void clear_and_update(String file_path, String[][] update_data){
        String new_data;
        try{
            write_file = new FileWriter(file_path);
            write_buffer = new BufferedWriter(write_file);

            for (String[] row : update_data){
                new_data = String.join(";",row);
                write_buffer.write(new_data);
                write_buffer.newLine();
            }
            write_buffer.close();
        } catch(Exception e){
            System.out.println("");
        }
    }

    public void update_row(String file_path, String id, String update_data){
        String[] current_data = read_file.read(file_path);
        String[] data;
        String new_data;

        try{
            write_file = new FileWriter(file_path);
            write_buffer = new BufferedWriter(write_file);

            for (int i = 0; i < current_data.length; i++){
                data = current_data[i].split(";");
                if (data[0].equals(id)){
                    write_buffer.write(update_data);
                    write_buffer.newLine();
                }else{
                    write_buffer.write(current_data[i]);
                    write_buffer.newLine();
                }
            }
            write_buffer.close();
        } catch(Exception e){
            System.out.println(e);
        }
    }
}
