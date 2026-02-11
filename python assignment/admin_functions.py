def unassign_lecturer(lecturer_list):
    unassign = []
    for lecturer_module_id in lecturer_list:
        module_id = lecturer_module_id["module_id"].strip()

        if module_id == "*":
            unassign.append({"id": lecturer_module_id["id"], "Lecturer Name": lecturer_module_id["name"]})

    return unassign


def unassign_module(module_list):
    unassign = []
    for module_lecturer_id in module_list:
        lecturer_id = module_lecturer_id["teacher_id"].strip()

        if lecturer_id == "@":
            unassign.append({"id": module_lecturer_id["id"], "Module Name": module_lecturer_id["name"]})

    return unassign


# Check if the module name is valid
def is_valid_module_name(module_name, module_list):
    for module in module_list:
        if module["name"].lower().strip('"') == module_name.lower():
            print("This module name has been taken.")
            return False

    return True


from common_functions import get_next_id
from common_functions import is_valid_quoted_input
from common_functions import is_valid_id
from common_functions import update_file
from common_functions import modify_input


def create_module(lecturer_list, course_list, module_list, lists, files):
    new_module_id = get_next_id(module_list, "M")
    print(f"\nNew Module ID: {new_module_id}")

    while True:
        new_module_name = input("\nPlease input a new module name (Input 'exit' to cancel): ").strip()

        if new_module_name.lower() == 'exit':
            print("\nModule creation cancelled.")
            return

        if is_valid_quoted_input(new_module_name):
            if is_valid_module_name(new_module_name, module_list):
                new_module_name = modify_input(new_module_name)
                break

    print("\nThese are unassigned lecturers:")

    unassign_lecturer_list = unassign_lecturer(lecturer_list)
    for lecturer in unassign_lecturer_list:
        print(lecturer)

    while True:
        new_lecturer_id = input(
            "\nPlease input a lecturer ID or '@' if not sure (type 'exit' to cancel): ").strip().upper()

        if new_lecturer_id.lower() == "exit":
            print("\nModule creation cancelled.")
            return

        if new_lecturer_id == "@":
            break  # Allow users to skip assigning a lecturer

        if is_valid_id(new_lecturer_id, unassign_lecturer_list):
            break  # Exit loop when valid ID is entered

    while True:
        print("\nPlease choose a course that this module belongs to:")
        count = 1
        for course in course_list:
            print(f"{count}.\nID: {course['id']}\nCourse Name: {course['name']}")
            count += 1

        new_course_id = input("Please input the course ID (type 'exit' to cancel): ").strip().upper()

        if new_course_id.lower() == "exit":
            print("\nModule creation cancelled.")
            return

        if is_valid_id(new_course_id, course_list):
            break

    new_module_data = {
        "id": new_module_id,
        "name": new_module_name,
        "teacher_id": new_lecturer_id,
        "course_id": new_course_id,
        "lesson_plans": "@",
        "schedules": "&",
        "lecture_notes": "$"
    }
    while True:

        print("\nAre you sure you want to create a new module with the following details?\n")
        print(f"\nModule ID     : {new_module_id}")
        print(f"Module Name   : {new_module_name}")
        print(f"Lecturer ID   : {new_lecturer_id}")
        print(f"Course ID     : {new_course_id}")
        print(f"Lesson Plans  : Not available")
        print(f"Schedules     : Not available")
        print(f"Lecture Notes : Not available")
        print("\n1. Yes")
        print("2. No")
        user_input_confirm = input("\nPlease input a number: ").strip()

        if user_input_confirm == "1":

            for lec in lecturer_list:
                if new_lecturer_id == lec["id"].strip():
                    lec['module_id'] = new_module_id
                    break

            # Add the new module to the module list
            lists[5].append(new_module_data)

            new_attendance_file = f"./dataEMS/attendance_{new_module_id}.txt"
            new_enrolment_file = f"./dataEMS/enrolment_{new_module_id}.txt"

            with open(new_attendance_file, "w") as file:
                pass

            with open(new_enrolment_file, "w") as file:
                pass

            # Save changes to files
            update_file(5, files, lists)
            update_file(3, files, lists)
            print("\nCreated successfully.")
            return True

        elif user_input_confirm == "2":
            print("\nCancelled successfully.")
            return

        else:
            print("\nInvalid Option.")


# Update module name
def update_module_name(module_list, module_id):
    for module in module_list:
        if module["id"] == module_id:
            while True:
                new_module_name = input("\nEnter new module name (type 'exit' to cancel): ").strip()

                if new_module_name.lower() == "exit":
                    print("\nUpdate cancelled.")
                    return

                if new_module_name.lower() == module["name"].strip('"').lower():
                    print("You have inserted an old module name.")
                    continue

                if is_valid_quoted_input(new_module_name):
                    if is_valid_module_name(new_module_name, module_list):
                        module["name"] = modify_input(new_module_name)
                        print(f"\nModule Name has been successfully updated to '{new_module_name}'.")
                        return True


from common_functions import update_lesson_plans
from common_functions import update_schedules
from common_functions import update_notes


def update_module(module_list, files, lists):
    while True:

        module_id = input("Enter the module ID that you want to update (type 'exit' to cancel): ").strip().upper()

        if module_id.lower() == 'exit':
            print("\nUpdate cancelled.")
            return

        if is_valid_id(module_id, module_list):
            break

    for mod in module_list:
        if module_id == mod["id"]:

            while True:
                print(f"\nYou have selected: \n")
                print(f"Module Name: {mod['name']}\n")
                lesson_plans = mod["lesson_plans"].strip('"')
                print(f"Lesson Plans: {lesson_plans}\n")
                schedules = mod["schedules"].strip('"')
                print(f"Schedules: {schedules}\n")
                link = mod["lecture_notes"].strip('"')
                print(f"Lecture Notes Link: {link}")

                print("\nWhich component would you like to update?")
                print("1. Module Name")
                print("2. Lesson Plans")
                print("3. Schedules")
                print("4. Lecture Notes")
                print("5. Back")

                user_input = input("\nEnter a number: ").strip()

                if user_input == "1":
                    update_module_name(module_list, module_id)

                elif user_input == "2":
                    update_lesson_plans(module_id, module_list, files, lists)

                elif user_input == "3":
                    update_schedules(module_id, module_list, files, lists)

                elif user_input == "4":
                    update_notes(module_list, module_id, files, lists)

                elif user_input == "5":
                    return

                else:
                    print("\nInvalid number. Please try again.")


def delete_module(module_list, lecturer_list, files, lists):
    while True:
        module_id = input(
            "\nPlease input the Module ID of a Module that you want to delete (type 'exit' to cancel): ").strip().upper()

        if module_id.lower() == 'exit':
            print("\nDeletion cancelled.")
            return

        if is_valid_id(module_id, module_list):
            break

    while True:
        print(f"\nAre you sure you want to delete {module_id}?")
        print("1. Yes")
        print("2. No")
        confirm = input("\nPlease input a number: ").strip()

        if confirm == "1":
            for module in module_list:
                if module["id"] == module_id:
                    module_list.remove(module)
                    break

            update_file(5, files, lists)

            for lecturer in lecturer_list:
                if lecturer["module_id"] == module_id:
                    lecturer["module_id"] = "*"
                    break

            update_file(3, files, lists)

            print(f"\nModule {module_id} has been deleted successfully.")
            return True

        elif confirm == "2":
            print("\nCancelled successfully!")
            return

        else:
            print("\nInvalid option.")


def assign_lecturer(module_list, lecturer_list, files, lists):
    unassign_module_list = unassign_module(module_list)
    if not unassign_module_list:
        print("\nNo modules to be assigned with lecturers.")
        return

    print("\nThese are the modules with no lecturers assigned:")
    for module in unassign_module_list:
        print(module)

    while True:
        module_id = input("\nPlease input a Module ID (type 'exit' to cancel): ").upper().strip()

        if module_id.lower() == 'exit':
            print("\nExiting.....")
            return

        if is_valid_id(module_id, unassign_module_list):
            break  # Valid module ID found, exit loop

    print("\nThese are unassigned lecturers:")
    unassign_lecturer_list = unassign_lecturer(lecturer_list)
    for lecturer in unassign_lecturer_list:
        print(lecturer)

    # Keep asking for a valid lecturer ID until one is entered
    while True:
        lecturer_id = input(
            f"\nPlease input a Lecturer ID to assign to {module_id} (type 'exit' to cancel): ").strip().upper()

        if lecturer_id.lower() == 'exit':
            print("\nExiting.....")
            return

        if is_valid_id(lecturer_id, unassign_lecturer_list):
            break  # Valid lecturer ID found, exit loop

    for mod in module_list:
        if module_id == mod["id"]:
            mod["teacher_id"] = lecturer_id
            update_file(5, files, lists)
            break

    for lec in lecturer_list:
        if lecturer_id == lec["id"]:
            lec["module_id"] = module_id
            update_file(3, files, lists)
            break

    print("\nAssigned Successfully.")
    return True


# View module info
def admin_view_module_info(module_list):
    while True:
        module_id = input("\nEnter module ID (type 'exit' to cancel): ").strip().upper()

        if module_id.lower() == "exit":
            print("\nExiting...")
            return

        if is_valid_id(module_id, module_list):
            break

    # Find the module details
    for module in module_list:
        if module["id"] == module_id:
            # Display module information
            print("\n===== Module Information =====")
            print("---------------------------------------------------------")
            print(f"Module ID: {module['id']}")
            module_name = module["name"].strip('"')
            print(f"Module Name: {module_name}")
            print(f"Teacher: {module['teacher_id']}")
            lesson_plans = module["lesson_plans"].strip('"')
            print(f"Lesson Plans: {lesson_plans}")
            schedule = module["schedules"].strip('"')
            print(f"Schedule: {schedule}")
            lecture_notes = module["lecture_notes"].strip('"')
            print(f"Lecture Notes Link: {lecture_notes}")
            print("---------------------------------------------------------")
            return True


def course_management(lists, files):
    while True:
        print("\n============== Module Management ==============")
        print("\n1. Create Module")
        print("2. Update Module")
        print("3. Delete Module")
        print("4. Assign Lecturers")
        print("5. View Module Info")
        print("6. Back")
        user_input = input("\nPlease input a number: ").strip()

        if user_input == "1":
            create_module(lists[3], lists[7], lists[5], lists, files)

        elif user_input == "2":
            update_module(lists[5], files, lists)

        elif user_input == "3":
            delete_module(lists[5], lists[3], files, lists)

        elif user_input == "4":
            assign_lecturer(lists[5], lists[3], files, lists)

        elif user_input == "5":
            admin_view_module_info(lists[5])

        elif user_input == "6":
            return

        else:
            print("\nInvalid option. Please try again")


from common_functions import load_module_ids
from common_functions import is_valid_name
from common_functions import is_valid_ic
from common_functions import update_phone_no
from common_functions import change_username
from common_functions import valid_password
from common_functions import update_emergency_name
from common_functions import update_emergency_no


def manage_student(student_list, course_list, module_list, files, lists):
    while True:
        student_id = input("\nEnter the student ID that you want to update (or type exit to cancel): ").strip().upper()

        if student_id.lower() == 'exit':
            print("\nUpdate cancelled.")
            return

        if is_valid_id(student_id, lists[2]):
            break

    module_ids = load_module_ids(student_id, lists[5])

    for stud in student_list:
        if student_id == stud["id"]:

            print(f"\nYou have selected:")
            print(f"\nStudent Name            : {stud['name']}")
            print(f"Student IC              : {stud['ic']}")
            print(f"Student No              : {stud['phone_no']}")
            username = stud["username"].strip('"')
            print(f"Student Username        : {username}")
            password = stud["password"].strip('"')
            print(f"Student Password        : {password}")
            print(f"Student Emergency Name  : {stud['emergency_name']}")
            print(f"Student Emergency No    : {stud['emergency_no']}")
            print(f"Student Intake Code     : {stud['intake_code']}")
            print(f"Student Course ID       : {stud['course_id']}")

            count = 1
            print("\nModules Enrolled: ")
            for module_id in module_ids:
                for module in module_list:
                    if module["id"] == module_id:
                        module_name = module["name"].strip('"')
                        print(f"{count}. {module_id}, {module_name}")
                        count += 1
                        break

            while True:
                print("\nWhich component would you like to update?")
                print("1. Student Name")
                print("2. Student IC")
                print("3. Student No")
                print("4. Student Username  ")
                print("5. Student Password  ")
                print("6. Student Emergency Name ")
                print("7. Student Emergency No")
                print("8. Student Intake Code ")
                print("9. Student Course ID ")
                print("10. Back")

                user_input = input("\nEnter a number: ").strip()

                if user_input == "1":
                    while True:
                        new_student_name = input("\nEnter new student name (type 'exit' to cancel): ").strip().title()

                        if new_student_name.lower() == "exit":
                            print("\nUpdate cancelled.")
                            break

                        if is_valid_name(new_student_name):
                            stud['name'] = new_student_name
                            print(f"\nStudent Name has successfully been updated to '{new_student_name}'.")
                            update_file(2, files, lists)
                            break

                elif user_input == "2":
                    while True:
                        new_student_ic = input("\nEnter new student ic (type' exit' to cancel): ").strip()

                        if new_student_ic.lower() == "exit":
                            print("\nUpdate cancelled.")
                            break

                        if is_valid_ic(new_student_ic):
                            stud['ic'] = new_student_ic
                            print(f"\nStudent IC has successfully been updated to '{new_student_ic}'.")
                            update_file(2, files, lists)
                            break

                elif user_input == "3":
                    update_phone_no(student_id, 2, files, lists)

                elif user_input == "4":
                    change_username(student_id, 2, files, lists)

                elif user_input == "5":
                    while True:
                        new_student_password = input("\nEnter new student password (type 'exit' to cancel): ").strip()

                        if new_student_password.lower() == "exit":
                            print("\nUpdate cancelled.")
                            break

                        if valid_password(new_student_password):
                            new_student_password = modify_input(new_student_password)
                            stud['password'] = new_student_password
                            print(f"\nStudent Password has successfully been updated to '{new_student_password}'.")
                            update_file(2, files, lists)
                            break

                elif user_input == "6":
                    update_emergency_name(student_id, 2, files, lists)

                elif user_input == "7":
                    update_emergency_no(student_id, 2, files, lists)

                elif user_input == "8":
                    while True:
                        new_student_intake_code = input(
                            "\nEnter new student intake code (type 'exit' to cancel): ").strip().upper()

                        if new_student_intake_code.lower() == "exit":
                            print("\nUpdate cancelled.")
                            break

                        stud['intake_code'] = new_student_intake_code
                        print(f"\nStudent Intake Code has successfully been updated to '{new_student_intake_code}'.")
                        update_file(2, files, lists)
                        break

                elif user_input == "9":
                    while True:
                        new_student_course = input(
                            "\nEnter new student course id (type 'exit' to cancel): ").strip().upper()

                        if new_student_course.lower() == "exit":
                            print("\nUpdate cancelled.")
                            break

                        if is_valid_id(new_student_course, course_list):
                            stud['course_id'] = new_student_course
                            print(f"\nStudent Course ID has successfully been updated to '{new_student_course}'.")
                            update_file(2, files, lists)
                            break

                elif user_input == "10":
                    print("\nExiting.....")
                    update_file(2, files, lists)
                    return

                else:
                    print("\nPlease input a valid option.")


def manage_lecturer(lecturer_list, lists, files):
    while True:
        lecturer_id = input("\nEnter the lecturer ID that you want to update (or type exit to back): ").strip().upper()

        if lecturer_id.lower() == 'exit':
            print("\nUpdate cancelled.")
            return

        if is_valid_id(lecturer_id, lecturer_list):
            break

    for lec in lecturer_list:
        if lecturer_id == lec["id"]:

            print(f"\nYou have selected:")
            print(f"\nLecturer Name            : {lec['name']}")
            print(f"Lecturer IC              : {lec['ic']}")
            print(f"Lecturer No              : {lec['phone_no']}")
            username = lec["username"].strip('"')
            print(f"Lecturer Username        : {username}")
            password = lec["password"].strip('"')
            print(f"Lecturer Password        : {password}")
            print(f"Lecturer Emergency Name  : {lec['emergency_name']}")
            print(f"Lecturer Emergency No    : {lec['emergency_no']}")
            print(f"Lecturer Module ID       : {lec['module_id']}")

            while True:
                print("\nWhich component would you like to update?")
                print("\n1. Lecturer Name")
                print("2. Lecturer IC")
                print("3. Lecturer No")
                print("4. Lecturer Username  ")
                print("5. Lecturer Password  ")
                print("6. Lecturer Emergency Name ")
                print("7. Lecturer Emergency No")
                print("8. Back")

                user_input = input("\nEnter a number: ").strip()

                if user_input == "1":
                    while True:
                        new_lecturer_name = input("\nEnter new lecturer name (type 'exit' to cancel): ").strip().title()

                        if new_lecturer_name.lower() == "exit":
                            print("\nUpdate cancelled.")
                            break

                        if is_valid_name(new_lecturer_name):
                            lec['name'] = new_lecturer_name
                            print(f"\nLecturer Name has successfully been updated to '{new_lecturer_name}'.")
                            update_file(3, files, lists)
                            break

                elif user_input == "2":
                    while True:
                        new_lecturer_ic = input("\nEnter new lecturer ic (type 'exit' to cancel): ").strip()

                        if new_lecturer_ic.lower() == "exit":
                            print("\nUpdate cancelled.")
                            break

                        if is_valid_ic(new_lecturer_ic):
                            lec['ic'] = new_lecturer_ic
                            print(f"\nLecturer IC has successfully been updated to '{new_lecturer_ic}'.")
                            update_file(3, files, lists)
                            break

                elif user_input == "3":
                    update_phone_no(lecturer_id, 3, files, lists)

                elif user_input == "4":
                    change_username(lecturer_id, 3, files, lists)

                elif user_input == "5":
                    while True:
                        new_lecturer_password = input("\nEnter new lecturer password (type 'exit' to cancel): ").strip()

                        if new_lecturer_password.lower() == "exit":
                            print("\nUpdate cancelled.")
                            break

                        if valid_password(new_lecturer_password):
                            lec['password'] = modify_input(new_lecturer_password)
                            print(f"\nLecturer Password has successfully been updated to '{new_lecturer_password}'.")
                            update_file(3, files, lists)
                            break

                elif user_input == "6":
                    update_emergency_name(lecturer_id, 3, files, lists)

                elif user_input == "7":
                    update_emergency_no(lecturer_id, 3, files, lists)

                elif user_input == "8":
                    print("\nExiting.....")
                    return

                else:
                    print("\nPlease input a valid option.")


def manage_staff(staff_list, lists, files):
    while True:

        staff_id = input("\nEnter the staff ID that you want to update (or type exit to back): ").strip().upper()

        if staff_id.lower() == 'exit':
            print("\nUpdate cancelled.")
            return

        if is_valid_id(staff_id, staff_list):
            break

    for staff in staff_list:
        if staff_id == staff["id"]:

            print(f"\nYou have selected:\n")
            print(f"Staff Name            : {staff['name']}")
            print(f"Staff IC              : {staff['ic']}")
            print(f"Staff No              : {staff['phone_no']}")
            print(f"Staff Username        : {staff['username']}")
            print(f"Staff Password        : {staff['password']}")
            print(f"Staff Emergency Name  : {staff['emergency_name']}")
            print(f"Staff Emergency No    : {staff['emergency_no']}")

            while True:
                print("\nWhich component would you like to update?")
                print("1. Staff Name")
                print("2. Staff IC")
                print("3. Staff No")
                print("4. Staff Username  ")
                print("5. Staff Password  ")
                print("6. Staff Emergency Name ")
                print("7. Staff Emergency No")
                print("8. Back")

                user_input = input("\nEnter a number: ").strip()

                if user_input == "1":
                    while True:
                        new_staff_name = input("\nEnter new staff name (type 'exit' to cancel): ").strip().title()

                        if new_staff_name.lower() == "exit":
                            print("\nUpdate cancelled.")
                            break

                        if is_valid_name(new_staff_name):
                            staff['name'] = new_staff_name
                            print(f"\nStaff Name has successfully been updated to '{new_staff_name}'.")
                            update_file(4, files, lists)
                            break

                elif user_input == "2":
                    while True:
                        new_staff_ic = input("\nEnter new staff ic (type 'exit' to cancel): ").strip()

                        if new_staff_ic.lower() == "exit":
                            print("\nUpdate cancelled.")
                            break

                        if is_valid_ic(new_staff_ic):
                            staff['ic'] = new_staff_ic
                            print(f"\nStaff IC has successfully been updated to '{new_staff_ic}'.")
                            update_file(4, files, lists)
                            break

                elif user_input == "3":
                    update_phone_no(staff_id, 4, files, lists)

                elif user_input == "4":
                    change_username(staff_id, 4, files, lists)

                elif user_input == "5":
                    while True:
                        new_staff_password = input("\nEnter new staff password (type 'exit' to cancel): ").strip()

                        if new_staff_password.lower() == "exit":
                            print("\nUpdate cancelled.")
                            break

                        if valid_password(new_staff_password):
                            staff['password'] = modify_input(new_staff_password)
                            print(f"\nStaff Password has successfully been updated to '{new_staff_password}'.")
                            update_file(4, files, lists)
                            break

                elif user_input == "6":
                    update_emergency_name(staff_id, 4, files, lists)

                elif user_input == "7":
                    update_emergency_no(staff_id, 4, files, lists)

                elif user_input == "8":
                    print("\nExiting.....")
                    return

                else:
                    print("\nPlease input a valid option.")


def system_administration(lists, files):
    while True:
        print("\n============== System Administration ==============")
        print("\n1. Manage Student")
        print("2. Manage Lecturer")
        print("3. Manage Staff")
        print("4. Back")
        user_input = input("\nPlease input a number: ").strip()

        if user_input == "1":
            manage_student(lists[2], lists[7], lists[5], files, lists)

        elif user_input == "2":
            manage_lecturer(lists[3], lists, files)

        elif user_input == "3":
            manage_staff(lists[4], lists, files)

        elif user_input == "4":
            print("\nExiting.....")
            return

        else:
            print("\nPlease input a valid option.")


def calculate_exam_assign_grade(module_id, list):
    grade_count = {}
    total_score = 0
    num_score = 0

    for item in list:
        if module_id == item['module_id']:
            grade = item['grade']
            score = float(item['score'])

            if grade in grade_count:
                grade_count[grade] += 1

            else:
                grade_count[grade] = 1

            total_score += score
            num_score += 1

    average_score = total_score / num_score if num_score > 0 else 0

    print("\nGrade Distribution:")
    for grade, count in grade_count.items():
        percentage = (count / num_score) * 100
        print(f"{grade}: {count} ({percentage:.2f}%)")

    print(f"\nAverage Score: {average_score:.2f}\n")

    return True


def calculate_module_grade(module_id, module_grades_list):
    grade_count = {}
    num_score = 0
    for module_grade in module_grades_list:
        if module_id == module_grade['module_id']:
            grade = module_grade['final_grade']

            if grade in grade_count:
                grade_count[grade] += 1

            else:
                grade_count[grade] = 1

            num_score += 1

    print("\nGrade Distribution:")
    for grade, count in grade_count.items():
        percentage = (count / num_score) * 100
        print(f"{grade}: {count} ({percentage:.2f}%)")

    return True


from common_functions import track_grades


def generate_student_academic(lists):
    while True:
        student_id = input("\nEnter the student ID (or type exit to back): ").strip().upper()

        if student_id.lower() == 'exit':
            print("\nReport generation cancelled.")
            return

        if is_valid_id(student_id, lists[2]):
            track_grades(student_id, lists[5], lists[8], lists[6], lists[10], lists[9])
            return True


def generate_insti_academic(lists):
    while True:
        module_id = input("\nPlease input a module id (type 'exit' to cancel): ").strip().upper()

        if module_id.lower() == 'exit':
            print("\nReport generation cancelled.")
            return

        if is_valid_id(module_id, lists[5]):
            print(f"\n============== Academic Performance Report for {module_id} ==============")

            print(f"\n---- Exam Grades ----")
            calculate_exam_assign_grade(module_id, lists[10])

            print(f"\n---- Assignments Grades ----")
            calculate_exam_assign_grade(module_id, lists[8])

            print(f"\n---- Final Module Grades ----")
            calculate_module_grade(module_id, lists[9])

            print(f"\n============== End ==============\n")
            return True


def calculate_percentage_stud(student_records):
    total = len(student_records)

    present = sum(1 for record in student_records if record["status"] == "Present")
    late = sum(1 for record in student_records if record["status"] == "Late")
    absent = sum(1 for record in student_records if record["status"] == "Absent")
    excused = sum(1 for record in student_records if record["status"] == "Excused")

    if total > 0:
        present_percentage = (present / total) * 100
        late_percentage = (late / total) * 100
        absent_percentage = (absent / total) * 100
        excused_percentage = (excused / total) * 100

    else:
        present_percentage = late_percentage = absent_percentage = excused_percentage = 0

    return present_percentage, late_percentage, absent_percentage, excused_percentage


def generate_report_stud(student_id, module_id, student_records):
    present_percentage, late_percentage, absent_percentage, excused_percentage = calculate_percentage_stud(
        student_records)

    print(f"\n========== Attendance Report {module_id} ==========\n")

    print(f"Student : {student_id}\n")
    print(f"Present : {present_percentage:.2f}%\n")
    print(f"Late    : {late_percentage:.2f}%\n")
    print(f"Absent  : {absent_percentage:.2f}%\n")
    print(f"Excused : {excused_percentage:.2f}%")

    print("\n------- Details -------")

    if not student_records:
        print("\nNo attendance records for this module.")

    else:
        for record in student_records:
            print(f"Date: {record['date']}, Status: {record['status']}\n")

    print("\n========== End ==========\n")

    return True


from common_functions import open_attendance_file


def attendance_report_stud(student_list, module_list):
    while True:
        student_id = input("\nPlease input a student id (type 'exit' to cancel): ").strip().upper()

        if student_id.lower() == "exit":
            print("\nReport generation cancelled.")
            return

        if is_valid_id(student_id, student_list):

            module_ids = load_module_ids(student_id, module_list)
            if not module_ids:
                print("\nThis student did not enrol in any modules. Report cannot be generated.")

            else:
                break

    for module_id in module_ids:
        attendance_list = open_attendance_file(module_id)
        student_records = [record for record in attendance_list if
                           record['student_id'] == student_id]

        if not student_records:
            print("\nNo attendance records for this student.")

        generate_report_stud(student_id, module_id, student_records)
    return True


def calculate_percentage_insti(attendance_data):
    present = sum(1 for record in attendance_data if record["status"] == "Present")
    late = sum(1 for record in attendance_data if record["status"] == "Late")
    absent = sum(1 for record in attendance_data if record["status"] == "Absent")
    excused = sum(1 for record in attendance_data if record["status"] == "Excused")
    total = len(attendance_data)

    if total > 0:
        present_percentage = (present / total) * 100
        late_percentage = (late / total) * 100
        absent_percentage = (absent / total) * 100
        excused_percentage = (excused / total) * 100

    else:
        present_percentage = late_percentage = absent_percentage = excused_percentage = 0

    return present_percentage, late_percentage, absent_percentage, excused_percentage


def generate_report_insti(module_id, attendance_data):
    present_percentage, late_percentage, absent_percentage, excused_percentage = calculate_percentage_insti(
        attendance_data)

    print(f"\n========== Attendance Report {module_id} ==========\n")

    print(f"Present : {present_percentage:.2f}%\n")
    print(f"Late    : {late_percentage:.2f}%\n")
    print(f"Absent  : {absent_percentage:.2f}%\n")
    print(f"Excused : {excused_percentage:.2f}%")

    print("\n========== End ==========\n")


def attendance_report_insti(module_list):
    while True:
        module_id = input("\nPlease input a module id (type 'exit' to cancel): ").strip().upper()

        if module_id.lower() == "exit":
            print("\nReport generation cancelled.")
            return

        if is_valid_id(module_id, module_list):
            break

    attendance_data = open_attendance_file(module_id)
    generate_report_insti(module_id, attendance_data)
    return True


def financial_report_stud(financial_list, student_list):
    while True:
        student_id = input(
            "\nPlease input the Student ID that you want the financial report to be generated (type 'exit' to cancel): ").strip().upper()

        if student_id.lower() == "exit":
            print("\nReport generation cancelled.")
            return

        if is_valid_id(student_id, student_list):
            break

    for financial in financial_list:
        if financial["student_id"] == student_id:
            print(f"\n======= Financial Report for Student {student_id} ========\n")

            print(f"\nTotal Amount: {float(financial['total_paid']) + float(financial['outstanding']):.2f}\n")
            print(f"Total Paid  : {financial['total_paid']}\n")
            print(f"Outstanding : {financial['outstanding']}\n")

            print(f"\n===================== End ======================\n")
            break

    return True


def report_generation_financial_insti(financial_data):
    total_paid = sum(float(record['total_paid']) for record in financial_data)
    total_outstanding = sum(float(record['outstanding']) for record in financial_data)
    total_amount = total_paid + total_outstanding

    print(f"\n========== Financial Report ==========\n")

    print(f"\nTotal Amount     : RM {total_amount:.2f}\n")
    print(f"Total Received   : RM {total_paid:.2f}\n")
    print(f"Total Outstanding: RM {total_outstanding:.2f}\n")

    print(f"\n================ End ==================\n")


def report_generation(lists):
    while True:
        print(f"\n========== Report Generation ==========\n")
        print("\n1. Academic Performance Report")
        print("2. Attendance Report")
        print(f"3. Financial Report")
        print("4. Back\n")
        user_input_report = input("\nPlease input a number: ").strip()

        if user_input_report == "1":
            while True:
                print("\nWhich academic report you would like to generate?")
                print("\n1. Student")
                print("2. Institution")
                print("3. Back")
                user_input = input("\nPlease input a number: ").strip()

                if user_input == "1":
                    generate_student_academic(lists)

                elif user_input == "2":
                    generate_insti_academic(lists)

                elif user_input == "3":
                    print("\nExiting.....")
                    break

                else:
                    print("\nInvalid input, please try again.")

        elif user_input_report == "2":
            while True:
                print("\nWhich Attendance Report would you like to generate?")
                print("\n1. Student")
                print("2. Institution")
                print("3. Back")

                user_input = input("\nPlease input a number: ").strip()

                if user_input == "1":
                    attendance_report_stud(lists[2], lists[5])

                elif user_input == "2":
                    attendance_report_insti(lists[5])

                elif user_input == "3":
                    print("\nExiting.....")
                    break

                else:
                    print("\nInvalid option. Please try again.")

        elif user_input_report == "3":
            while True:
                print("\nWhich Financial Report would you like to generate")
                print("\n1. Student")
                print("2. Institution")
                print("3. Back")

                user_input = input("\nPlease input a number: ").strip()

                if user_input == "1":
                    financial_report_stud(lists[16], lists[2])

                elif user_input == "2":
                    report_generation_financial_insti(lists[16])

                elif user_input == "3":
                    print("\nExiting.....")
                    break

                else:
                    print("\nInvalid input, please try again.")

        elif user_input_report == "4":
            print("\nExiting.....")
            return

        else:
            print("\nPlease input a valid option.")


def view_admin_profile(current_user_id, admin_list):
    for admin in admin_list:
        if current_user_id == admin["id"]:
            print(f"\n===== User Profile =====")
            print(f"\nName: {admin['name']}")
            print(f"\nID: {admin['id']}")
            print(f"\nIC number: {admin['ic']}")
            print(f"\nPhone number: {admin['phone_no']}")
            username = admin["username"].strip('"')
            print(f"\nUsername: {username}")

            if admin["emergency_name"] == "&":
                print("\n*Emergency contact name needs to be updated.")

            else:
                print(f"\nEmergency Contact Name: {admin['emergency_name']}")

            if admin["emergency_no"] == "!":
                print("\n*Emergency contact number needs to be updated.")

            else:
                print(f"\nEmergency Contact Number: {admin['emergency_no']}")


from common_functions import edit_profile


# "Profile" menu
def admin_profile(role, current_user_id, admin_list, files, lists):
    while True:

        print("\n===== Profile =====")
        print("\na. View Profile")
        print("b. Edit Profile")
        print("c. Return to Main Menu")

        user_input = input("\nPlease choose an action to perform (a/b/c): ").strip().lower()

        if user_input == "a":
            view_admin_profile(current_user_id, admin_list)

        elif user_input == "b":
            edit_profile(current_user_id, role, files, lists)

        elif user_input == "c":
            return True

        else:
            print("\nPlease insert a valid option (a/b/c).")


from common_functions import timetable_management
from common_functions import resource_allocation
from common_functions import open_enrolment_file
from common_functions import view_enrolled_students
from common_functions import enrol_student
from common_functions import remove_student


# Student enrolment
def admin_student_enrolment(module_list, student_list, course_list):
    while True:

        print("\n===== Student Enrolment =====")
        print("\na. View Enrolled Students")
        print("b. Enrol Student")
        print("c. Remove Student")
        print("d. Back")

        user_input = input("\nPlease choose an action to perform (a/b/c/d): ").strip().lower()

        if user_input not in ["a", "b", "c", "d"]:
            print("\nPlease insert a valid option (a/b/c/d).")
            continue

        if user_input == "d":
            return

        while True:
            module_id = input("\nEnter module ID (type 'exit' to cancel): ").strip().upper()

            if module_id.lower() == "exit":
                print("\nExiting...")
                break

            if is_valid_id(module_id, module_list):

                enrolment_list = open_enrolment_file(module_id)

                if user_input == "a":
                    view_enrolled_students(enrolment_list, student_list)

                if user_input == "b":
                    enrol_student(enrolment_list, student_list, module_list, module_id, course_list)

                if user_input == "c":
                    remove_student(enrolment_list, student_list, module_id)

                break


from common_functions import announcements


def admin_main_menu(role, current_user_id, files, lists):
    while True:
        print(f"\n======= Welcome to the Administrative Account ========")
        print("\n1. System Administration")
        print("2. Module Management")
        print("3. Class Schedule")
        print("4. Resource Allocation")
        print("5. Report Generation")
        print("6. Student Enrolment")
        print("7. Announcements")
        print("8. Profile")
        print("9. Log Out")
        user_input_main = input("\nPlease choose a number: ").strip()

        if user_input_main == "1":
            system_administration(lists, files)

        elif user_input_main == "2":
            course_management(lists, files)

        elif user_input_main == "3":
            timetable_management(lists[12], lists[5], lists[11], files, lists)

        elif user_input_main == "4":
            resource_allocation(lists[12], files, lists, lists[3], lists[11])

        elif user_input_main == "5":
            report_generation(lists)

        elif user_input_main == "6":
            admin_student_enrolment(lists[5], lists[2], lists[7])

        elif user_input_main == "7":
            announcements(lists[14], files, lists)

        elif user_input_main == "8":
            admin_profile(role, current_user_id, lists[1], files, lists)

        elif user_input_main == "9":
            while True:
                print("\nAre you sure you want to log out?")
                print("1. Yes")
                print("2. No")
                user_input = input("\nPlease input a number: ").strip()

                if user_input == "1":
                    return

                elif user_input == "2":
                    break

                else:
                    print("\nInvalid option. Please try again")

        else:
            print("\nInvalid option. Please try again.")