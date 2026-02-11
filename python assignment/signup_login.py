from common_functions import split_respect_quotes
from common_functions import valid_username
from common_functions import is_valid_quoted_input
from common_functions import modify_input
from common_functions import valid_password
from common_functions import update_role_list
from common_functions import update_file
from common_functions import is_valid_ic
from common_functions import is_valid_name
from common_functions import is_valid_id

from admin_functions import admin_main_menu
from student_functions import student_main_menu
from teacher_functions import teacher_main_menu
from staff_functions import staff_main_menu


# Check if the user's account is new
def is_new_user(role, id, lists):
    for role_name in lists[role]:
        if id == role_name["id"] and role_name["username"] != "$":
            print("\nAccount already exists.")

            return False

    return True


# Check if the user's personal information is valid
def valid_personal(role, name, id, ic, lists):
    for role_name in lists[role]:
        if name == role_name["name"] and id == role_name["id"] and ic == role_name["ic"]:
            print("\nValid information.")
            return True

    print("\nInvalid information.")
    return False


# Check if the username and password are valid and matched
def valid_user(role, username, password, lists):
    for role_name in lists[role]:

        if username == role_name["username"].strip('"') and password == role_name["password"].strip('"'):
            return True

    return False


# Save current user's id after login
def save_user_id(role, username, lists):
    for role_name in lists[role]:
        if username == role_name["username"]:
            user_id = role_name["id"]
            return user_id


# Read data from a file and save the data into a list
def open_file(list_no, files, lists):
    file_path = files[list_no]
    try:
        with open(file_path, "r") as file:

            first_char = file.read(1)
            if not first_char:
                lists[list_no] = []
                return True

            file.seek(0)

            for line in file:
                newline = line.strip("\n")
                if newline.startswith("#") or not newline:
                    continue

                data = split_respect_quotes(newline)

                if list_no == 1 or list_no == 4:
                    data_dic = {"id": data[0], "name": data[1], "ic": data[2],
                                "phone_no": data[3], "username": data[4], "password": data[5],
                                "emergency_name": data[6], "emergency_no": data[7]}

                if list_no == 2:
                    data_dic = {"id": data[0], "name": data[1], "ic": data[2],
                                "phone_no": data[3], "username": data[4], "password": data[5],
                                "emergency_name": data[6], "emergency_no": data[7], "intake_code": data[8],
                                "course_id": data[9]}

                if list_no == 3:
                    data_dic = {"id": data[0], "name": data[1], "ic": data[2],
                                "phone_no": data[3], "username": data[4], "password": data[5],
                                "emergency_name": data[6], "emergency_no": data[7], "module_id": data[8]}

                if list_no == 5:
                    data_dic = {"id": data[0], "name": data[1], "teacher_id": data[2], "course_id": data[3],
                                "lesson_plans": data[4], "schedules": data[5], "lecture_notes": data[6]}

                if list_no == 6:
                    data_dic = {"id": data[0], "name": data[1], "module_id": data[2], "teacher_id": data[3],
                                "link": data[4]}

                if list_no == 7:
                    data_dic = {"id": data[0], "name": data[1]}

                if list_no == 8:
                    data_dic = {"id": data[0], "assignment_id": data[1], "module_id": data[2], "teacher_id": data[3],
                                "student_id": data[4], "grade": data[5], "score": data[6], "feedback": data[7]}

                if list_no == 9:
                    data_dic = {"id": data[0], "student_id": data[1], "module_id": data[2], "teacher_id": data[3],
                                "final_grade": data[4]}

                if list_no == 10:
                    data_dic = {"id": data[0], "module_id": data[1], "teacher_id": data[2], "student_id": data[3],
                                "grade": data[4], "score": data[5]}

                if list_no == 11:
                    data_dic = {"id": data[0], "module_id": data[1], "day": data[2], "time": data[3], "venue": data[4],
                                "teacher_id": data[5], "resource_id": data[6]}

                if list_no == 12:
                    data_dic = {"id": data[0], "name": data[1], "status": data[2], "location": data[3],
                                "lecturer_id": data[4]}

                if list_no == 13:
                    data_dic = {"id": data[0], "module_id": data[1], "student_id": data[2], "feedback": data[3]}

                if list_no == 14:
                    data_dic = {"id": data[0], "title": data[1], "details": data[2]}

                if list_no == 15:
                    data_dic = {"id": data[0], "student_id": data[1], "question": data[2], "reply": data[3]}

                if list_no == 16:
                    data_dic = {"id": data[0], "student_id": data[1], "total_paid": data[2], "outstanding": data[3]}

                if list_no == 17:
                    data_dic = {"id": data[0], "event_name": data[1], "date": data[2], "time": data[3],
                                "venue": data[4]}

                lists[list_no].append(data_dic)

            return True

    except FileNotFoundError:
        print(f"Error: The file '{file_path}' was not found. Please check the filename or path.")
        return False


# Sign up function
def sign_up(role, lists, files):
    while True:
        name = input("\nName (type 'exit' to cancel): ").strip().title()

        if name.lower() == "exit":
            print("\nAccount creation cancelled.")
            return

        if is_valid_name(name):
            break

    while True:
        id = input("\nID (type 'exit' to cancel): ").strip().upper()

        if id.lower() == "exit":
            return

        if is_valid_id(id, lists[role]):
            break

    while True:
        ic = input("\nIC (type 'exit' to cancel): ").strip()

        if ic.lower() == "exit":
            return

        if is_valid_ic(ic):
            break

    if valid_personal(role, name, id, ic, lists):

        if is_new_user(role, id, lists):

            while True:
                username = input("\nUsername (or type 'quit' to cancel): ").strip()

                if username.lower() == "quit":
                    print("\nAccount creation cancelled.")
                    return False

                if valid_username(username, lists) and is_valid_quoted_input(username):
                    username = modify_input(username)
                    break

            print("\nPassword Requirements:")
            print("\n*Min 8 characters")
            print("*At least 1 lowercase alphabet")
            print("*At least 1 uppercase alphabet")
            print("*At least 1 digit or number")
            print("*At least 1 special character")
            print("*No spaces")

            while True:
                password = input("\nPassword (type 'quit' to cancel): ").strip()

                if password.lower() == "quit":
                    print("\nAccount creation cancelled.")
                    return False

                if valid_password(password) and is_valid_quoted_input(password):
                    password = modify_input(password)
                    break

            update_role_list(id, role, username, password, lists)
            update_file(role, files, lists)
            print("\nAccount created successfully. Please proceed to log in.")
            return True

        else:
            while True:
                user_input = input("\nProceed to log in? (yes/no) ").strip().lower()

                if user_input == "yes":
                    return True

                elif user_input == "no":
                    return False

                else:
                    print("\nPlease insert a valid input.")

    else:
        return False


# Log in function
def log_in(role, lists, files):
    attempts = 3

    while attempts > 0:
        username = input("\nUsername (type 'exit' to quit): ").strip()

        if username.lower() == "exit":
            print("\nExiting...")
            return

        password = input("\nPassword (type 'exit' to quit): ").strip()

        if password.lower() == "exit":
            print("\nExiting...")
            return

        if valid_user(role, username, password, lists):
            print("\nLogged in successfully.")
            current_user_id = save_user_id(role, username, lists)

            menus = {
                1: admin_main_menu,
                2: student_main_menu,
                3: teacher_main_menu,
                4: staff_main_menu
            }

            menus[role](role, current_user_id, files, lists)
            return True

        else:
            attempts -= 1
            print(f"\nInvalid username or password. {attempts} attempts left.")

    print("\nToo many failed attempts. Returning to Welcome Page...")
    return False
