# Respect commas inside quotes and split the values into a list
def split_respect_quotes(newline):
    data = []
    current_value = ""
    inside_quotes = False

    for char in newline:

        if char == '"':
            inside_quotes = not inside_quotes
            current_value += char

        elif char == "," and not inside_quotes:
            data.append(current_value)
            current_value = ""

        else:
            current_value += char

    data.append(current_value)

    return data


# Add quotes to the input that is not inside quotes and contains comma
def modify_input(input):
    if "," in input and not (input.startswith('"') and input.endswith('"')):
        input = f'"{input}"'

    return input


# Check if the user input is properly quoted
def is_valid_quoted_input(input):
    if '"' in input:
        if input.startswith('"') and input.endswith('"') and input.count('"') == 2:
            return True

        else:
            print("\nPlease insert an input with proper quotes.")
            return False

    return True


# Check if the username has not been taken
def valid_username(username, lists):
    if username == "$" or username == '"$"':
        print("\nPlease insert a valid username.")
        return False

    role_lists = [lists[1], lists[2], lists[3], lists[4]]

    for role_list in role_lists:
        for role_name in role_list:
            if username == role_name["username"].strip('"'):
                print("\nUsername has been taken.")
                return False

    return True


# Check if the password meets all the requirements
def valid_password(password):
    status = True

    if len(password) < 8:
        print("*Password must be at least 8 characters long.")
        status = False

    if not any(char.islower() for char in password):
        print("*Password must contain at least one lowercase letter.")
        status = False

    if not any(char.isupper() for char in password):
        print("*Password must contain at least one uppercase letter.")
        status = False

    if not any(char.isdigit() for char in password):
        print("*Password must contain at least one digit.")
        status = False

    special_chars = "!@#$%^&*(),.?/:{}|<>[];"
    if not any(char in special_chars for char in password):
        print("*Password must contain at least one special character (!@#$%^&* etc.).")
        status = False

    if " " in password:
        print("*Password must not contain any spaces.")
        status = False

    return status


# Update the new username and password into the role list
def update_role_list(id, role, username, password, lists):
    for role_name in lists[role]:
        if id == role_name["id"]:
            role_name["username"] = username
            role_name["password"] = password
            return True


# Write the updated list into the file
def update_file(file_no, files, lists):
    file_path = files[file_no]

    try:
        with open(file_path, "w") as file:
            for data in lists[file_no]:
                file.write(",".join(map(str, data.values())) + "\n")

    except FileNotFoundError:
        print(f"\nError: The file '{file_path}' was not found. Please check the filename or path.")


# Get the next id to store a new line of values
def get_next_id(list_name, prefix):
    start = len(prefix)
    existing_ids = [data["id"] for data in list_name if data["id"].startswith(prefix)]

    if not existing_ids:
        next_id = f"{prefix}1"
        return next_id

    numbers = [int(id[start:]) for id in existing_ids]

    next_number = max(numbers) + 1
    next_id = f"{prefix}{next_number}"
    return next_id


# Check if phone number is valid
def valid_phone_no(phone_no):
    if 9 < len(phone_no) < 12 and phone_no.isdigit():
        return True

    else:
        print("\nPlease insert a valid mobile phone number.")
        return False


# Update the new phone number
def update_phone_no(current_user_id, role_no, files, lists):
    while True:
        new_phone_no = input("\nNew phone number (type 'exit' to cancel): ").strip()

        if new_phone_no.lower() == "exit":
            print("\nUpdate cancelled.")
            return

        if not valid_phone_no(new_phone_no):
            continue

        for role in lists[role_no]:
            if current_user_id == role["id"]:

                if new_phone_no == role["phone_no"]:
                    print("\nIt looks like you have entered an old phone number.")
                    break

                role["phone_no"] = new_phone_no
                update_file(role_no, files, lists)
                print("\nPhone number has been updated.")
                return True


# Change username
def change_username(current_user_id, role_no, files, lists):
    while True:
        new_username = input("\nNew username (type 'exit' to cancel): ").strip()

        if new_username.lower() == "exit":
            print("\nUpdate cancelled.")
            return

        if not is_valid_quoted_input(new_username):
            continue

        for role in lists[role_no]:
            if current_user_id == role["id"]:

                if new_username == role["username"].strip('"'):
                    print("\nYou have entered an old username.")
                    break

                if valid_username(new_username, lists):
                    new_username = modify_input(new_username)
                    role["username"] = new_username
                    update_file(role_no, files, lists)
                    print("\nUsername updated.")
                    return True


# Change password
def change_password(current_user_id, role_no, files, lists):
    while True:

        current_password = input("\nCurrent password (type 'exit' to cancel): ").strip()

        if current_password.lower() == "exit":
            print("\nUpdate cancelled.")
            return

        if not is_valid_quoted_input(current_password):
            continue

        for role in lists[role_no]:
            if current_user_id == role["id"]:
                if current_password != role["password"].strip('"'):
                    print("\nPassword incorrect.")
                    break

                print("\nPassword Requirements:")
                print("\n*Min 8 characters")
                print("*At least 1 lowercase alphabet")
                print("*At least 1 uppercase alphabet")
                print("*At least 1 digit or number")
                print("*At least 1 special character")
                print("*No spaces")

                while True:
                    new_password = input("\nNew Password (type 'exit' to cancel): ").strip()

                    if new_password.lower() == "exit":
                        print("\nUpdate cancelled.")
                        return

                    if new_password == current_password:
                        print("\nYou have entered an old password.")
                        continue

                    if not is_valid_quoted_input(new_password):
                        continue

                    if valid_password(new_password):
                        new_password = modify_input(new_password)
                        role["password"] = new_password
                        update_file(role_no, files, lists)
                        print("\nPassword updated.")
                        return True


# Check if the name is valid
def is_valid_name(name):
    if all(char.isalpha() or char.isspace() for char in name):
        return True

    print("\nPlease insert a valid name.")
    return False


# Update emergency contact name
def update_emergency_name(current_user_id, role_no, files, lists):
    for role in lists[role_no]:
        if current_user_id == role["id"]:

            if role["emergency_name"] != "&":

                while True:
                    user_input = input(
                        "\nYou have already updated your emergency contact name. Are you sure you want to change (yes/no) ? ").strip().lower()

                    if user_input == "yes":
                        break

                    elif user_input == "no":
                        print("\nUpdate cancelled.")
                        return

                    else:
                        print("\nPlease insert a valid input.")

            while True:
                new_emergency_name = input("\nEmergency Contact Name (type 'exit' to cancel): ").strip().title()

                if new_emergency_name.lower() == "exit":
                    print("\nUpdate cancelled.")
                    return

                if is_valid_name(new_emergency_name):
                    if new_emergency_name == role["emergency_name"]:
                        print("\nYou have inserted an old emergency name.")
                        continue

                    role["emergency_name"] = new_emergency_name.title()
                    update_file(role_no, files, lists)
                    print("\nEmergency Contact Name updated.")
                    return True


# Update emergency contact number
def update_emergency_no(current_user_id, role_no, files, lists):
    for role in lists[role_no]:
        if current_user_id == role["id"]:

            if role["emergency_no"] != "!":
                while True:
                    user_input = input(
                        "\nYou have already updated your emergency contact number. Are you sure you want to change (yes/no) ? ").strip().lower()

                    if user_input == "yes":
                        break

                    elif user_input == "no":
                        print("\nUpdate cancelled.")
                        return

                    else:
                        print("\nPlease insert a valid input.")

            while True:
                new_emergency_no = input("\nEmergency Contact Number (type 'exit' to cancel): ").strip()

                if new_emergency_no.lower() == "exit":
                    print("\nUpdate cancelled.")
                    return

                if valid_phone_no(new_emergency_no):
                    if new_emergency_no == role["emergency_no"]:
                        print("\nYou have inserted an old emergency phone number.")
                        continue

                    role["emergency_no"] = new_emergency_no
                    update_file(role_no, files, lists)
                    print("\nEmergency Contact Number updated.")
                    return True


# "Edit Profile" menu
def edit_profile(current_user_id, role, files, lists):
    while True:

        print("\n===== Edit Profile =====")
        print("\n1. Update Phone Number")
        print("2. Change Username")
        print("3. Change Password")
        print("4. Update Emergency Contact Name")
        print("5. Update Emergency Contact Number")
        print("6. Return to 'Profile' Menu")

        user_input = input("\nPlease choose an action to perform (1-6): ").strip()

        if user_input == "1":
            update_phone_no(current_user_id, role, files, lists)

        elif user_input == "2":
            change_username(current_user_id, role, files, lists)

        elif user_input == "3":
            change_password(current_user_id, role, files, lists)

        elif user_input == "4":
            update_emergency_name(current_user_id, role, files, lists)

        elif user_input == "5":
            update_emergency_no(current_user_id, role, files, lists)

        elif user_input == "6":
            return True

        else:
            print("\nPlease insert a valid number (1-6).")


# Save relevant assignment IDs of a module into a list
def save_assignment_id(assignment_list, module_id):
    assignment_ids = []

    for assignment in assignment_list:
        if module_id == assignment["module_id"]:
            assignment_ids.append(assignment["id"])

    return assignment_ids


# Open and read the enrolment file of a specific module
def open_enrolment_file(module_id):
    file_path = f"./dataEMS/enrolment_{module_id}.txt"

    enrolment_list = []
    try:
        with open(file_path, "r") as file:
            for line in file:
                newline = line.strip("\n")

                if newline.startswith("#") or not newline:
                    continue

                data = newline.split(",")

                data_dic = {"id": data[0], "student_id": data[1]}
                enrolment_list.append(data_dic)

        return enrolment_list

    except FileNotFoundError:
        print(f"\nError: The file '{file_path}' was not found. Please check the filename or path.")


# Check if an ID is valid
def is_valid_id(id, list_name):
    for item in list_name:
        if item["id"] == id:
            return True

    print("\nPlease insert a valid ID.")
    return False


# Check if the date is valid
def is_valid_date(date):
    status = True

    if len(date) != 10 or date[4] != "-" or date[7] != "-" or date.count("-") != 2:
        print("\nPlease insert the date in a valid format (YYYY-MM-DD).")
        return False

    data = date.split("-")

    if not (data[0].isdigit() and data[1].isdigit() and data[2].isdigit()):
        status = False

    if status:
        year = int(data[0])
        month = int(data[1])
        day = int(data[2])

        if (year % 4 == 0 and year % 100 != 0) or year % 400 == 0:
            is_leap_year = True

        else:
            is_leap_year = False

        if month < 1 or month > 12:
            status = False

        if month == 2:
            if is_leap_year:
                if day < 1 or day > 29:
                    status = False

            else:
                if day < 1 or day > 28:
                    status = False

        if month in [1, 3, 5, 7, 8, 10, 12]:
            if day < 1 or day > 31:
                status = False

        else:
            if day < 1 or day > 30:
                status = False

    if not status:
        print("\nPlease insert a valid date.")

    return status


# Prints the assignment's name
def print_assign_name(assignment_list, assignment_id):
    for assignment in assignment_list:
        if assignment["id"] == assignment_id:
            assignment_name = assignment["name"].strip('"')
            print(f"\nAssignment Name: {assignment_name}")
            break


# Get student's assignment grades
def get_assign_grade(assign_grade_list, student_id, assignment_list, module_id):
    assignment_ids = save_assignment_id(assignment_list, module_id)

    for assign_id in assignment_ids:
        print_assign_name(assignment_list, assign_id)

        assign_grade_exists = False
        for assign_grade in assign_grade_list:
            if assign_grade["student_id"] == student_id and assign_grade["assignment_id"] == assign_id:
                assign_grade_exists = True
                print(f"Grade: {assign_grade['grade']}")
                print(f"Score: {assign_grade['score']}")
                feedback = assign_grade["feedback"].strip('"')
                print(f"Lecturer Feedback: {feedback}")
                break

        if not assign_grade_exists:
            print("*This assignment has not been graded yet.")


# Get student's exam grade
def get_exam_grade(exam_grade_list, module_id, student_id):
    for exam_grade in exam_grade_list:
        if exam_grade["module_id"] == module_id and exam_grade["student_id"] == student_id:
            print(f"\nGrade: {exam_grade['grade']}")
            print(f"Score: {exam_grade['score']}")
            return True

    print("\n*No record for exam grade.")
    return False


# Get student's final module grade
def get_final_grade(module_grade_list, student_id, module_id):
    for module_grade in module_grade_list:
        if module_grade["student_id"] == student_id and module_grade["module_id"] == module_id:
            print(f"\nModule Final Grade: {module_grade['final_grade']}")
            return True

    print("\n*No record for module final grade.")
    return False


# Check if the schedule is new
def is_new_schedule(module_id, timetable_list):
    for timetable in timetable_list:
        if timetable["module_id"] == module_id:
            print("\nThis module's timetable is already scheduled.")
            return False

    return True


# Function to load the IDs of the modules enrolled by the student
def load_module_ids(student_id, module_list):
    module_ids = []
    for module in module_list:
        file_path = f"./dataEMS/enrolment_{module['id']}.txt"
        try:
            with open(file_path, "r") as file:
                lines = file.readlines()

                if not lines:
                    continue

                for enroll_line in lines:
                    line = enroll_line.strip("\n")
                    data = split_respect_quotes(line)
                    if data[1] == student_id:
                        module_ids.append(module["id"])
                        break  # Stop checking once found

        except FileNotFoundError:
            print(f"\nError: The file '{file_path}' was not found. Please check the filename or path.")

    return module_ids


# Check if venue is available at that day and time
def is_valid_venue(day, time, venue, timetable_list):
    for timetable in timetable_list:
        if timetable["day"] == day and timetable["time"] == time and timetable["venue"] == venue:
            print("\nThis venue is not available for this day and time.")
            return False

    return True


# Check if the resource is available and update resource details
def borrow_resource(resource_list, files, lists, teacher_list, resource_id):

    for line in resource_list:
        if line["id"] == resource_id:
            location = input("\nEnter location (type 'exit' to cancel): ").strip().upper()

            if location.lower() == "exit":
                print("\nUpdate cancelled.")
                return

            while True:
                lecturer_id = input("\nEnter lecturer ID (type 'exit' to cancel): ").strip().upper()

                if lecturer_id.lower() == "exit":
                    print("\nUpdate cancelled.")
                    return

                if is_valid_id(lecturer_id, teacher_list):
                    break

            line["status"] = "Not Available"
            line["location"] = location
            line["lecturer_id"] = lecturer_id
            update_file(12, files, lists)
            print(f"\nThe details of resource {line['id']} are updated.")
            return True


# Check if the timetable exists
def is_exist_timetable(module_id, timetable_list):
    for timetable in timetable_list:
        if timetable["module_id"] == module_id:
            return True

    print("\nThis module's timetable has not been scheduled yet. Rescheduling cannot be performed.")
    return False


# Function to track grades for student
def track_grades(student_id, module_list, assign_grade_list, assignment_list, exam_grade_list, module_grade_list):
    enrolled_module_ids = load_module_ids(student_id, module_list)

    if not enrolled_module_ids:
        print("\nNo enrolled modules. Performance report cannot be generated.")
        return

    print("\n===== Student Performance Report =====")
    print(f"\nStudent ID: {student_id}")

    count = 1
    for module in module_list:
        if module["id"] in enrolled_module_ids:
            module_name = module["name"].strip('"')
            print(f"\n{count}. Module: {module_name}")

            print("\n--- Assignments ---")
            get_assign_grade(assign_grade_list, student_id, assignment_list, module["id"])

            print("\n--- Exam ---")
            get_exam_grade(exam_grade_list, module["id"], student_id)
            get_final_grade(module_grade_list, student_id, module["id"])
            count += 1

    return


# Add new line into a file
def append_new_line(file_no, files, new_dic):
    file_path = files[file_no]
    try:
        with open(file_path, "a") as file:
            file.write(",".join(map(str, new_dic.values())) + "\n")

    except FileNotFoundError:
        print(f"\nError: The file '{file_path}' was not found. Please check the filename or path.")


# Add new resource
def add_resource(resource_list, files):
    new_resource_id = get_next_id(resource_list, "R")
    print(f"\nNew Resource ID: {new_resource_id}")

    item_name = input("\nPlease input resource name (Input 'exit' to cancel): ").strip()

    if item_name.lower() == 'exit':
        print("\nResource addition cancelled.")
        return

    new_resource = {
        "id": new_resource_id,
        "name": item_name,
        "status": "Available",
        "location": "$",
        "lecturer_id": "&"
    }

    resource_list.append(new_resource)
    append_new_line(12, files, new_resource)

    print(f"\nNew resource added: {item_name}")
    return True


# 'Update Resource Status' Menu
def update_resource_status(resource_list, files, lists, teacher_list, timetable_list):
    while True:
        chosen3 = input(
            f"\n===== Update Resource Status =====\n\n1: Borrow Item \n2: Return Item \n3: Return to 'Resource Allocation' Menu\n\nEnter Number: ").strip()

        if chosen3 == "3":
            return

        elif chosen3 == "1":  # borrow item
            available_resource_ids = []
            while True:
                print("\nAvailable Resources:")
                for resource in resource_list:
                    if resource["status"] == "Available":
                        print(f"ID: {resource['id']}, Name: {resource['name']}")
                        available_resource_ids.append(resource["id"])

                resource_id = input("\nPlease enter a resource ID (type 'exit' to cancel): ").strip().upper()

                if resource_id.lower() == "exit":
                    print("\nUpdate cancelled.")
                    break

                if resource_id in available_resource_ids:
                    borrow_resource(resource_list, files, lists, teacher_list, resource_id)
                    break

                else:
                    print("\nPlease insert a valid resource ID.")

        elif chosen3 == "2":  # return item

            print("\nBorrowed resources:")
            count = 1
            resource_ids = []
            for resource in resource_list:
                if resource["status"] == "Not Available":
                    print(
                        f"\n{count}.\nID: {resource['id']}\nName: {resource['name']}\nLocation: {resource['location']}\nLecturer ID: {resource['lecturer_id']}")
                    count += 1
                    resource_ids.append(resource['id'])

            while True:

                if not resource_ids:
                    print("\nNo resources are borrowed.")
                    break

                resource_id = input(
                    "\nEnter the resource ID to update status (type 'exit' to cancel): ").strip().upper()

                if resource_id.lower() == "exit":
                    print("\nUpdate cancelled.")
                    break

                if resource_id in resource_ids:
                    for line in resource_list:
                        if line["id"] == resource_id:
                            line["status"] = "Available"
                            line["location"] = "$"
                            line["lecturer_id"] = "&"
                            update_file(12, files, lists)
                            print(f"\nResource {resource_id}'s status is updated to 'Available'.")
                            break

                    for timetable in timetable_list:
                        if timetable["resource_id"] == resource_id:
                            timetable["resource_id"] = "$"
                            update_file(11,files,lists)
                            break

                    return True

                else:
                    print("\nPlease insert a valid resource ID.")

        else:
            print(f"\nPlease insert a valid input (1-3). ")


# Delete resource
def delete_resource(resource_list, files, lists):
    if not resource_list:
        print("\nNo resource available. This action cannot be performed.")
        return

    print("\nResources:")
    for resource in resource_list:
        print(f"ID: {resource['id']}, Name: {resource['name']}, Status: {resource['status']}")

    while True:
        resource_id = input("\nPlease enter resource id (type 'exit' to cancel): ").strip().upper()

        if resource_id.lower() == "exit":
            print("\nResource removal cancelled.")
            return

        if is_valid_id(resource_id, resource_list):
            break

    for resource in resource_list:
        if resource["id"] == resource_id:
            resource_list.remove(resource)
            update_file(12, files, lists)
            print(f"\nResource {resource_id} removed successfully.")
            return True


# 'Resource Allocation' Menu
def resource_allocation(resource_list, files, lists, teacher_list, timetable_list):
    while True:
        print("\n===== Resource =====")
        print("\n1. Add New Resource")
        print("2. Update Resource Status")
        print("3. Delete Resource")
        print("4. Back")

        user_input = input("\nPlease choose an action to perform (1-4): ").strip()

        if user_input == "1":
            add_resource(resource_list, files)

        elif user_input == "2":
            update_resource_status(resource_list, files, lists, teacher_list, timetable_list)

        elif user_input == "3":
            delete_resource(resource_list, files, lists)

        elif user_input == "4":
            return

        else:
            print("\nInvalid option. Please try again.")


# Schedule new timetable
def schedule_timetable(timetable_list, module_list, files):
    while True:
        module_id = input("\nEnter module ID (type 'exit' to cancel): ").strip().upper()

        if module_id.lower() == "exit":
            print("\nUpdate cancelled.")
            return

        if is_valid_id(module_id, module_list):
            if is_new_schedule(module_id, timetable_list):
                break

    while True:
        day = input("\nEnter day (type 'exit' to cancel): ").strip().capitalize()

        if day.lower() == "exit":
            print("\nUpdate cancelled.")
            return

        if day in ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"]:
            break

        else:
            print("\nPlease insert a valid day.")

    time = input("\nEnter time (e.g. 3.45p.m.-5.45p.m.) (type 'exit' to cancel): ").strip()

    if time.lower() == "exit":
        print("\nUpdate cancelled.")
        return

    while True:
        venue = input("\nEnter venue (type 'exit' to cancel): ").strip().upper()

        if venue.lower() == "exit":
            print("\nUpdate cancelled.")
            return

        if is_valid_venue(day, time, venue, timetable_list):
            break

    new_id = get_next_id(timetable_list, "TT")

    for module in module_list:
        if module["id"] == module_id:
            teacher_id = module["teacher_id"]
            break

    new_timetable = {"id": new_id, "module_id": module_id, "day": day, "time": time,
                     "venue": venue, "teacher_id": teacher_id, "resource_id": "$"}

    timetable_list.append(new_timetable)
    append_new_line(11, files, new_timetable)

    print(f"\nTimetable for module {module_id} is successfully added.")


# Update class day
def update_class_day(timetable_list, module_id, files, lists):
    while True:
        day = input("\nEnter day (type 'exit' to cancel): ").strip().capitalize()

        if day.lower() == "exit":
            print("\nUpdate cancelled.")
            return

        if day in ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"]:

            for timetable in timetable_list:
                if timetable["module_id"] == module_id:
                    if timetable["day"] == day:
                        print("\nYou have inserted an old day.")
                        break

                    else:
                        timetable["day"] = day
                        update_file(11, files, lists)
                        print("\nThe day for this timetable is updated.")
                        return True

        else:
            print("\nPlease insert a valid day.")


# Update class time
def update_class_time(timetable_list, module_id, files, lists):
    while True:
        time = input("\nEnter time (e.g. 3.45p.m.-5.45p.m.) (type 'exit' to cancel): ").strip()

        if time.lower() == "exit":
            print("\nUpdate cancelled.")
            return

        for timetable in timetable_list:
            if timetable["module_id"] == module_id:
                if timetable["time"] == time:
                    print("\nYou have inserted an old time.")
                    break

                else:
                    timetable["time"] = time
                    update_file(11, files, lists)
                    print("\nThe time for this timetable is updated.")
                    return True


# Update class venue
def update_class_venue(timetable_list, module_id, files, lists):
    while True:
        venue = input("\nEnter venue (type 'exit' to cancel): ").strip().upper()

        if venue.lower() == "exit":
            print("\nUpdate cancelled.")
            return

        for timetable in timetable_list:
            if timetable["module_id"] == module_id:
                if timetable["venue"] == venue:
                    print("\nYou have inserted an old venue.")
                    break

                else:
                    for timetable in timetable_list:
                        if timetable["module_id"] == module_id:
                            module_day = timetable["day"]
                            module_time = timetable["time"]
                            break

                    if is_valid_venue(module_day, module_time, venue, timetable_list):
                        for timetable in timetable_list:
                            if timetable["module_id"] == module_id:
                                timetable["venue"] = venue
                                update_file(11, files, lists)
                                print("\nThe venue of this timetable is updated.")
                                return True


# Get resource name
def get_resource_name(resource_list, resource_id):
    for resource in resource_list:
        if resource["id"] == resource_id:
            return resource["name"]


# Update class resource
def update_resource(timetable_list, module_id, resource_list, files, lists):
    for timetable in timetable_list:
        if timetable["module_id"] == module_id:

            if timetable["resource_id"] != "$":
                while True:
                    user_input = input(
                        "Resource has been allocated for this class. Are you sure you want to update (yes/no)? ").strip().lower()

                    if user_input == "yes":
                        break

                    elif user_input == "no":
                        print("\nUpdate cancelled.")
                        return

                    else:
                        print("\nPlease insert a valid input.")

            while True:
                available_resource_ids = []
                print("\nAvailable Resources:")
                for resource in resource_list:
                    if resource["status"] == "Available":
                        print(f"ID: {resource['id']}, Name: {resource['name']}")
                        available_resource_ids.append(resource["id"])

                resource_id = input("\nPlease enter a resource ID (type 'exit' to cancel): ").strip().upper()

                if resource_id.lower() == "exit":
                    print("\nUpdate cancelled.")
                    break

                if resource_id not in available_resource_ids:
                    print("\nPlease insert a valid resource ID.")
                    continue

                if timetable["resource_id"] != "$":
                    ori_resource_name = get_resource_name(resource_list, timetable["resource_id"])
                    new_resource_name = get_resource_name(resource_list, resource_id)
                    if ori_resource_name == new_resource_name:
                        print(f"\nA '{new_resource_name}' has already been allocated for this class.")
                        continue

                for resource in resource_list:
                    if timetable["resource_id"] != "$":
                        if resource["id"] == timetable["resource_id"]:
                            resource["status"] = "Available"
                            resource["location"] = "$"
                            resource["lecturer_id"] = "&"
                            continue

                    if resource["id"] == resource_id:
                        resource["status"] = "Not Available"
                        resource["location"] = timetable["venue"]
                        resource["lecturer_id"] = timetable["teacher_id"]
                        update_file(12, files, lists)

                        timetable["resource_id"] = resource["id"]
                        update_file(11, files, lists)
                        print(f"\n{resource['name']} with ID {resource['id']} is allocated for this class.")

                return True


# Reschedule class
def reschedule_class(resource_list, module_list, timetable_list, files, lists):
    while True:
        module_id = input("\nEnter module ID (type 'exit' to cancel): ").strip().upper()

        if module_id.lower() == "exit":
            print("\nUpdate cancelled.")
            return

        if is_valid_id(module_id, module_list) and is_exist_timetable(module_id, timetable_list):
            print("\nYou have selected:")
            for timetable in timetable_list:
                if timetable["module_id"] == module_id:
                    print(f"\nDay: {timetable['day']}")
                    print(f"Time: {timetable['time']}")
                    print(f"Venue: {timetable['venue']}")
                    print(f"Teacher ID: {timetable['teacher_id']}")
                    if timetable["resource_id"] == "$":
                        print("*No resource is allocated for this class.")

                    else:
                        print(f"Resource ID: {timetable['resource_id']}")
                    break

            while True:
                user_input = input(
                    "\n1: Update Day\n2: Update Time\n3: Update Venue\n4: Update Resource\n5: Return to 'Timetable Management' Menu\n\nPlease insert a number (1-5): ").strip()

                if user_input == "5":
                    return

                elif user_input == "1":
                    update_class_day(timetable_list, module_id, files, lists)

                elif user_input == "2":
                    update_class_time(timetable_list, module_id, files, lists)

                elif user_input == "3":
                    update_class_venue(timetable_list, module_id, files, lists)

                elif user_input == "4":
                    update_resource(timetable_list, module_id, resource_list, files, lists)

                else:
                    print("\nPlease insert a valid input (1-5).")


# 'Timetable Management' Menu
def timetable_management(resource_list, module_list, timetable_list, files, lists):
    while True:
        chosen2 = input(
            f"\n===== Timetable Management =====\n\n1: Schedule New Timetable \n2: Reschedule Timetable\n3: Return to Main Menu \n\nEnter number (1-3): ").strip()

        if chosen2 == "3":
            return True

        elif chosen2 == "1":  # scheduling class
            schedule_timetable(timetable_list, module_list, files)

        elif chosen2 == "2":  # rescheduling class
            reschedule_class(resource_list, module_list, timetable_list, files, lists)

        else:
            print("\nPlease insert a valid input (1-3). ")


# Update lesson plans
def update_lesson_plans(module_id, module_list, files, lists):
    for module in module_list:
        if module_id == module["id"]:

            if module["lesson_plans"] != "@":
                while True:
                    user_input = input(
                        "\nThe lesson plans for this module has been updated. Are you sure you want to change (yes/no)? ").strip().lower()

                    if user_input == "yes":
                        break

                    elif user_input == "no":
                        print("\nUpdate cancelled.")
                        return

                    else:
                        print("\nPlease insert a valid input.")

            while True:

                new_lesson_plans = input("\nLesson Plans (type 'exit' to cancel): ").strip()

                if new_lesson_plans.lower() == "exit":
                    print("\nUpdate cancelled.")
                    return

                if new_lesson_plans == module["lesson_plans"].strip('"'):
                    print("\nYou have inserted the old lesson plans.")
                    continue

                if is_valid_quoted_input(new_lesson_plans):
                    new_lesson_plans = modify_input(new_lesson_plans)
                    module["lesson_plans"] = new_lesson_plans
                    update_file(5, files, lists)
                    print("\nLesson plans updated.")
                    return True


# Check if the schedule is valid
def is_valid_schedule(teacher_module_id, module_list, new_schedules):
    for module in module_list:
        if teacher_module_id == module["id"]:
            course_id = module["course_id"]
            break

    for module in module_list:
        if module["course_id"] == course_id:
            if module["schedules"].strip('"') == new_schedules:
                print(f"\nThis schedule has been taken.")
                return False

    return True


# Update schedules
def update_schedules(module_id, module_list, files, lists):
    for module in module_list:
        if module_id == module["id"]:

            if module["schedules"] != "&":
                while True:
                    user_input = input(
                        "\nThe schedule of this module has been updated. Are you sure you want to change (yes/no)? ").strip().lower()

                    if user_input == "yes":
                        break

                    elif user_input == "no":
                        print("\nUpdate cancelled.")
                        return

                    else:
                        print("\nPlease insert a valid input.")

            while True:
                new_schedules = input(
                    "\nNew schedules *e.g. 'Wednesday (8.30a.m.-10.30a.m.)' (type 'exit' to cancel): ").strip()

                if new_schedules.lower() == "exit":
                    print("\nUpdate cancelled.")
                    return

                if new_schedules == module["schedules"].strip('"'):
                    print("\nYou have inserted an old schedule.")
                    continue

                if is_valid_schedule(module_id, module_list, new_schedules):
                    new_schedules = modify_input(new_schedules)

                    module["schedules"] = new_schedules
                    update_file(5, files, lists)
                    print("\nNew schedule updated.")
                    return True


# Update lecture notes
def update_notes(module_list, module_id, files, lists):
    for module in module_list:
        if module_id == module["id"]:

            if module["lecture_notes"] != "$":
                while True:
                    user_input = input(
                        "\nThe lecture notes of this module have been updated. Are you sure you want to change (yes/no)? ").strip().lower()

                    if user_input == "yes":
                        break

                    elif user_input == "no":
                        print("\nUpdate cancelled.")
                        return

                    else:
                        print("\nPlease insert a valid input.")

            while True:
                new_notes = input("\nLecture Notes Link (type 'exit' to cancel): ").strip()

                if new_notes.lower() == "exit":
                    print("\nUpdate cancelled.")
                    return

                if new_notes == module["lecture_notes"].strip('"'):
                    print("\nYou have inserted an old lecture notes link.")
                    continue

                new_notes = modify_input(new_notes)
                module["lecture_notes"] = new_notes
                update_file(5, files, lists)
                print("\nNew lecture notes updated.")
                return True


# Open the attendence file of a module and save the data into a list
def open_attendance_file(module_id):
    attendance_list = []

    file_path = f"./dataEMS/attendance_{module_id}.txt"

    try:
        with open(file_path, "r") as file:
            for line in file:
                newline = line.strip("\n")

                if newline.startswith("#") or not newline:
                    continue

                data = split_respect_quotes(newline)

                data_dic = {"id": data[0], "student_id": data[1], "date": data[2], "status": data[3]}

                attendance_list.append(data_dic)

    except FileNotFoundError:
        print(f"\nError: The file '{file_path}' was not found. Please check the filename or path.")

    return attendance_list


# Check if the IC is valid
def is_valid_ic(ic):
    if len(ic) != 12 or not ic.isdigit():
        print("\nPlease insert a valid IC.")
        return False

    return True


# Check if the student is enrolled in the module
def is_student_enrolled(enrolment_list, student_id):
    if any(student_id == enrolment["student_id"] for enrolment in enrolment_list):
        return True

    else:
        print("\nThis student is not enrolled in this module.")
        return False


# Open and append a new line into the enrolment file of a specific module
def append_new_enrolment(module_id, new_enrolment_list):
    file_path = f"./dataEMS/enrolment_{module_id}.txt"

    try:
        with open(file_path, "a") as file:
            for enrolment in new_enrolment_list:
                file.write(",".join(map(str, enrolment.values())) + "\n")

    except FileNotFoundError:
        print(f"\nError: The file '{file_path}' was not found. Please check the filename or path.")


# Open and write the enrolment file of a specific module
def update_enrolment_file(module_id, enrolment_list):
    file_path = f"./dataEMS/enrolment_{module_id}.txt"

    try:
        with open(file_path, "w") as file:
            for enrolment in enrolment_list:
                file.write(",".join(map(str, enrolment.values())) + "\n")

    except FileNotFoundError:
        print(f"\nError: The file '{file_path}' was not found. Please check the filename or path.")


# View enrolled students
def view_enrolled_students(enrolment_list, student_list):
    if enrolment_list:
        print("\nStudents List:")
        number = 1
        for enrolment in enrolment_list:
            for student in student_list:
                if enrolment["student_id"] == student["id"]:
                    print(f"\n{number}. Student ID: {student['id']}, Name: {student['name']}")
                    number += 1
                    break

    else:
        print("\nNo student has been enrolled into this module.")


# Check if the student can be enrolled into the module
def is_student_qualified(student_id, student_list, module_list, module_id, course_list):
    for student in student_list:
        if student_id == student["id"]:
            student_course_id = student["course_id"]
            break

    for module in module_list:
        if module_id == module["id"]:
            if student_course_id == module["course_id"]:
                return True

            else:
                for course in course_list:
                    if module["course_id"] == course["id"]:
                        course_name = course["name"].strip('"')
                        print(f"\nOnly students from {course_name} can be enrolled into this module.")
                        return False


# Enrol student into a module
def enrol_student(enrolment_list, student_list, module_list, module_id, course_list):
    new_enrolment_list = []

    while True:
        new_student_id = input("\nEnter the student's ID (type 'exit' to quit): ").strip().upper()

        if new_student_id.lower() == "exit":
            print("\nExiting...")
            break

        if any(new_student_id == enrolment["student_id"] for enrolment in enrolment_list):
            print("\nThis student is already enrolled in this module.")
            continue

        if is_valid_id(new_student_id, student_list) and is_student_qualified(new_student_id, student_list, module_list,
                                                                              module_id, course_list):
            new_id = get_next_id(enrolment_list, "ENR")
            new_enrolment = {"id": new_id, "student_id": new_student_id}
            enrolment_list.append(new_enrolment)
            new_enrolment_list.append(new_enrolment)
            print("\nSuccessfully enrolled.")

    if new_enrolment_list:
        append_new_enrolment(module_id, new_enrolment_list)


# Remove student from a module
def remove_student(enrolment_list, student_list, module_id):
    while True:
        student_id = input("\nEnter the Student's ID (type 'exit' to quit): ").strip().upper()

        if student_id.lower() == "exit":
            print("\nExiting...")
            return

        if is_valid_id(student_id, student_list) and is_student_enrolled(enrolment_list, student_id):

            for enrolment in enrolment_list:
                if student_id == enrolment["student_id"]:
                    enrolment_list.remove(enrolment)
                    print("\nSuccessfully removed.")
                    update_enrolment_file(module_id, enrolment_list)
                    return True


# Count the number of announcements
def count_announcements(announcement_list):
    count = 0
    for announcement in announcement_list:
        count += 1

    return count


# Print announcments
def view_announcements(announcement_list):
    print("\n===== Recent Announcements =====")

    no_of_announcement = count_announcements(announcement_list)

    if no_of_announcement == 0:
        print("\nNo announcements available.")

    if 0 < no_of_announcement < 6:
        for announcement in announcement_list:
            print(f"\nID: {announcement['id']}")
            title = announcement["title"].strip('"')
            details = announcement["details"].strip('"')
            print(f"\n----- {title} -----\n{details}")

    if no_of_announcement > 5:
        for announcement in announcement_list[-5:]:  # Show last 5 announcements
            print(f"\nID: {announcement['id']}")
            title = announcement["title"].strip('"')
            details = announcement["details"].strip('"')
            print(f"\n----- {title} -----\n{details}")


# Create announcement
def create_announcement(announcement_list, files):
    new_announcement_id = get_next_id(announcement_list, "ANN")
    print(f"\nNew Announcement ID: {new_announcement_id}")

    while True:
        title = input("\nPlease input a title for the announcement (Input 'exit' to cancel): ").strip()

        if title.lower() == 'exit':
            print("\nAnnouncement creation cancelled.")
            return

        if is_valid_quoted_input(title):
            break

    while True:
        details = input("\nPlease input the details for the announcement (Input 'exit' to cancel): ")

        if details.lower() == 'exit':
            print("\nAnnouncement creation cancelled.")
            return

        if is_valid_quoted_input(details):
            break

    while True:
        print("\nAre you sure you want to create a new announcement with the following details?\n")
        print(f"Title  : {title}")
        print(f"Details: {details}")
        print("\n1. Yes")
        print("2. No")
        user_input_confirm = input("\nPlease input a number: ").strip()

        if user_input_confirm == "1":
            title = modify_input(title)
            details = modify_input(details)
            new_announcement = {"id": new_announcement_id, "title": title, "details": details}
            announcement_list.append(new_announcement)
            append_new_line(14, files, new_announcement)
            print("\nCreated successfully.\n")
            return True

        elif user_input_confirm == "2":
            print("\nCancelled successfully.")
            return

        else:
            print("\nInvalid Option.")


def update_announcement(announcement_list, files, lists):
    print("\nAvailable Announcements:")

    if not announcement_list:
        print("\nNo announcements available. This action cannot be performed.")
        return

    for announcement in announcement_list:
        print(f"\nID: {announcement['id']}\nTitle: {announcement['title']}\nDetails: {announcement['details']}")

    while True:
        announcement_id = input(
            "\nEnter an announcement ID that you want to update (type 'exit' to cancel): ").strip().upper()

        if announcement_id.lower() == 'exit':
            print("\nUpdate cancelled.")
            return

        if is_valid_id(announcement_id, announcement_list):
            break

    for ann in announcement_list:
        if announcement_id == ann["id"]:

            while True:
                print(f"\nYou have selected: \n")
                print(f"Title  : {ann['title']}\n")
                print(f"Details: {ann['details']}")

                print("\nWhich component would you like to update?")
                print("1. Title")
                print("2. Details")
                print("3. Back")

                user_input = input("\nEnter a number: ").strip()

                if user_input == "1":
                    while True:
                        new_title = input("\nEnter a new title (type 'exit' to cancel): ").strip()

                        if new_title.lower() == "exit":
                            print("\nUpdate cancelled.")
                            break

                        if new_title == ann["title"]:
                            print("\nYou have inserted the old title.")
                            continue

                        if is_valid_quoted_input(new_title):
                            ann['title'] = modify_input(new_title)
                            print(f"\nTitle has successfully been updated.")
                            update_file(14, files, lists)
                            break

                elif user_input == "2":
                    while True:
                        new_details = input("\nEnter new details (type 'exit' to cancel): ").strip()

                        if new_details.lower() == "exit":
                            print("\nUpdate cancelled.")
                            break

                        if new_details == ann["details"]:
                            print("\nYou have inserted the old details.")
                            continue

                        if is_valid_quoted_input(new_details):
                            ann['details'] = modify_input(new_details)
                            print(f"\nDetails has successfully been updated.")
                            update_file(14, files, lists)
                            break

                elif user_input == "3":
                    return

                else:
                    print("Please input a valid number.")


# Remove announcement
def remove_announcement(announcement_list, files, lists):
    print("\nAvailable Announcements:")

    if not announcement_list:
        print("\nNo announcements available. This action cannot be performed.")
        return

    for announcement in announcement_list:
        print(f"\nID: {announcement['id']}\nTitle: {announcement['title']}\nDetails: {announcement['details']}")

    while True:
        announcement_id = input("\nPlease input an announcement ID to remove (type 'exit' to cancel): ").strip().upper()

        if announcement_id.lower() == "exit":
            print("\nAnnouncement removal cancelled.")
            return

        if is_valid_id(announcement_id, announcement_list):
            break

    for announcement in announcement_list:
        if announcement["id"] == announcement_id:
            announcement_list.remove(announcement)
            update_file(14, files, lists)
            print(f"\nAnnouncement {announcement_id} removed successfully.")
            return True


# Announcement menu
def announcements(announcement_list, files, lists):
    while True:
        print("\n===== Announcements =====")
        print("\n1. View Announcements")
        print("2. Create New Announcement")
        print("3. Update Announcement")
        print("4. Remove Announcement")
        print("5. Back")

        user_input = input("\nPlease choose an action to perform (1-5): ").strip()

        if user_input == "1":
            view_announcements(announcement_list)

        elif user_input == "2":
            create_announcement(announcement_list, files)

        elif user_input == "3":
            update_announcement(announcement_list, files, lists)

        elif user_input == "4":
            remove_announcement(announcement_list, files, lists)

        elif user_input == "5":
            return

        else:
            print("\nInvalid option. Please try again.")