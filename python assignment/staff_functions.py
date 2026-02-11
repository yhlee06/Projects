# Get the next student id to store a new student's data
def get_next_std_id(student_list):
    existing_std_ids = [student["id"] for student in student_list if student["id"].startswith("TP")]

    if not existing_std_ids:
        next_id = "TP001"
        return next_id

    numbers = [int(id[2:]) for id in existing_std_ids]

    next_number = max(numbers) + 1
    next_id = f"TP{next_number:03d}"
    return next_id


# Check if the student is new
def is_new_student(ic, student_list):
    for student in student_list:
        if student["ic"] == ic:
            print("\nThis student has already registered.")
            return False

    return True


from common_functions import is_valid_name
from common_functions import is_valid_ic
from common_functions import valid_phone_no
from common_functions import is_valid_id
from common_functions import get_next_id
from common_functions import append_new_line


# Register student
def register_student(student_list, course_list, financial_list, files):
    print("\nPlease insert the student's information:")
    student_id = get_next_std_id(student_list)

    while True:
        student_name = input("\nEnter Student Name (type 'exit' to cancel): ").strip().title()

        if student_name.lower() == "exit":
            print("\nStudent registration cancelled.")
            return

        if is_valid_name(student_name):
            break

    while True:
        student_ic = input("\nEnter Student IC (type 'exit' to cancel): ").strip()

        if student_ic.lower() == "exit":
            print("\nStudent registration cancelled.")
            return

        if is_valid_ic(student_ic):
            break

    if is_new_student(student_ic, student_list):

        while True:
            student_phone_no = input("\nEnter Student Phone Number (type 'exit' to cancel): ").strip()

            if student_phone_no.lower() == "exit":
                print("\nStudent registration cancelled.")
                return

            if valid_phone_no(student_phone_no):
                break

        student_intake_code = input("\nEnter Student Intake Code (type 'exit' to cancel): ").strip()

        if student_intake_code.lower() == "exit":
            print("\nStudent registration cancelled.")
            return

        while True:
            student_course_id = input("\nEnter Student Course ID (type 'exit' to cancel): ").strip().upper()

            if student_course_id.lower() == "exit":
                print("\nStudent registration cancelled.")
                return

            if is_valid_id(student_course_id, course_list):
                break

        print("\nPlease insert the student's financial details:")
        financial_id = get_next_id(financial_list, "F")

        while True:
            try:
                input_total_paid = input("\nEnter Total Fees Paid (type 'exit' to cancel): ").strip()

                if input_total_paid.lower() == "exit":
                    print("\nStudent registration cancelled.")
                    return

                input_total_paid = float(input_total_paid)

                total_paid = f"{input_total_paid:.2f}"
                break

            except ValueError:
                print("\nPlease insert a valid amount of money.")

        while True:
            try:
                input_outstanding = input("\nEnter Outstanding Fees (type 'exit' to cancel): ").strip()

                if input_outstanding.lower() == "exit":
                    print("\nStudent registration cancelled.")
                    return

                input_outstanding = float(input_outstanding)
                outstanding = f"{input_outstanding:.2f}"
                break

            except ValueError:
                print("\nPlease insert a valid amount of money.")

        new_student = {
            "id": student_id,
            "name": student_name,
            "ic": student_ic,
            "phone_no": student_phone_no,
            "username": "$",
            "password": "@",
            "emergency_name": "&",
            "emergency_no": "!",
            "intake_code": student_intake_code,
            "course_id": student_course_id
        }
        new_financial = {
            "id": financial_id,
            "student_id": student_id,
            "total_paid": total_paid,
            "outstanding": outstanding
        }

        student_list.append(new_student)
        append_new_line(2, files, new_student)

        financial_list.append(new_financial)
        append_new_line(16, files, new_financial)

        print(f"\nNew student added: {new_student}")
        return True


from common_functions import update_file


# Transfer course
def transfer_course(student_list, course_list, files, lists):
    while True:
        student_id = input("\nEnter Student ID (type 'exit' to cancel): ").strip().upper()

        if student_id.lower() == "exit":
            print("\nCourse transfer cancelled.")
            return

        student_found = False
        for student in student_list:
            if student["id"] == student_id:
                student_found = True
                print(f"\nStudent found: {student['name']} (Current Course: {student['course_id']})")

                while True:
                    new_course_id = input("\nEnter the new course ID (type 'exit' to cancel): ").strip().upper()

                    if new_course_id.lower() == "exit":
                        print("\nCourse transfer cancelled.")
                        return

                    if is_valid_id(new_course_id, course_list):
                        break

                student["course_id"] = new_course_id

                update_file(2, files, lists)
                print(f"\nStudent {student_id}'s course has been updated to {new_course_id}.")
                return True

        if not student_found:
            print("\nPlease insert a valid student ID.")


# Student withdrawal
def student_withdrawal(student_list, files, lists, financial_list):
    while True:
        student_id = input("\nEnter student ID (type 'exit' to cancel): ").strip().upper()

        if student_id.lower() == "exit":
            print("\nStudent withdrawal cancelled.")
            return

        if is_valid_id(student_id, student_list):
            for student in student_list:
                if student["id"] == student_id:
                    student_list.remove(student)
                    update_file(2, files, lists)
                    break

            for financial in financial_list:
                if financial["student_id"] == student_id:
                    financial_list.remove(financial)
                    update_file(16, files, lists)
                    break

            print(f"\n{student_id} is withdrawn successfully.")
            return True


# 'Update Student Records' Menu
def update_student_records(student_list, course_list, financial_list, files, lists):
    while True:
        chosen1 = input(
            f"\n===== Update Student Records =====\n\n1: Register Student \n2: Transfer Course \n3: Student Withdrawal\n4: Return to Main Menu \n\nEnter Number (1-4): ").strip()

        if chosen1 == "4":
            break

        elif chosen1 == "1":  # register student
            register_student(student_list, course_list, financial_list, files)

        elif chosen1 == "2":  # transfer course
            transfer_course(student_list, course_list, files, lists)

        elif chosen1 == "3":  # student withdrawal
            student_withdrawal(student_list, files, lists, financial_list)

        else:
            print("\nPlease insert a valid input (1-4).")


from common_functions import is_valid_quoted_input
from common_functions import modify_input
from common_functions import is_valid_date


# Add new event
def add_event(event_list, files):
    while True:
        event_name = input(f"\nEnter New Event (type 'exit' to cancel): ").strip()

        if event_name.lower() == "exit":
            print("\nEvent adding cancelled.")
            return

        if is_valid_quoted_input(event_name):
            event_name = modify_input(event_name)
            break

    while True:
        date = input(f"\nEnter Date (YYYY-MM-DD) (type 'exit' to cancel): ").strip()

        if date.lower() == "exit":
            print("\nEvent adding cancelled.")
            return

        if is_valid_date(date):
            break

    time = input(f"\nEnter Time (e.g. 3.45p.m.-5.45p.m.): ").strip()

    while True:
        location = input(f"\nEnter Location (type 'exit' to cancel): ").strip().upper()

        if location.lower() == "exit":
            print("\nEvent adding cancelled.")
            return

        if is_valid_event_venue(date, time, location, event_list):
            break

    new_event_id = get_next_id(event_list, "EV")

    new_event = {
        "id": new_event_id,
        "event_name": event_name,
        "date": date,
        "time": time,
        "venue": location
    }

    event_list.append(new_event)
    append_new_line(17, files, new_event)

    print(f"\nNew event added: {event_name}.")
    return True


# Delete event
def delete_event(event_list, files, lists):
    print("\nAvailable Events:")

    for event in event_list:
        event_name = event["event_name"].strip('"')
        print(f"\nEvent ID: {event['id']} \nEvent Name: {event_name}")

    while True:
        event_id = input("\nEnter the event ID to delete (type 'exit' to cancel): ").strip().upper()

        if event_id.lower() == "exit":
            print("\nEvent deletion cancelled.")
            return True

        if is_valid_id(event_id, event_list):
            break

    while True:
        confirm = input("\nAre you sure you want to delete this event? (yes/no)").strip().lower()

        if confirm == "yes":
            for event in event_list:
                if event["id"] == event_id:
                    event_list.remove(event)
                    update_file(17, files, lists)
                    print(f"\nEvent '{event_id}' deleted successfully.")
                    return True

        elif confirm == "no":
            print("\nDeletion cancelled.")
            return

        else:
            print("\nPlease insert a valid input.")


# Update event date
def update_event_date(event_list, event_id, files, lists):
    while True:
        date = input("\nEnter Date (YYYY-MM-DD) (type 'exit' to cancel): ").strip()

        if date.lower() == "exit":
            print("\nUpdate cancelled.")
            return

        if is_valid_date(date):
            for event in event_list:
                if event["id"] == event_id:
                    if event["date"] == date:
                        print("\nYou have inserted an old date.")
                        break

                    else:
                        event["date"] = date
                        update_file(17, files, lists)
                        print(f"\nThe date of this event is updated to '{date}'.")
                        return True


# Update event time
def update_event_time(event_list, event_id, files, lists):
    while True:
        time = input("\nEnter Time (e.g. 3.45p.m.-5.45p.m.) (type 'exit' to cancel): ").strip()

        if time.lower() == "exit":
            print("\nUpdate cancelled.")
            return

        for event in event_list:
            if event["id"] == event_id:
                if event["time"] == time:
                    print("\nYou have inserted an old time.")
                    break

                else:
                    event["time"] = time
                    update_file(17, files, lists)
                    print(f"\nThe time of this event is updated to '{time}'.")
                    return True


# Check if the event's venue is taken
def is_valid_event_venue(date, time, location, event_list):
    for event in event_list:
        if event["date"] == date and event["time"] == time and event["venue"] == location:
            print("\nThis venue has been taken for this date and time.")
            return False

    return True


# Update event venue
def update_event_venue(event_list, event_id, files, lists):
    while True:
        venue = input("\nEnter Venue (type 'exit' to cancel): ").strip().upper()

        if venue.lower() == "exit":
            print("\nUpdate cancelled.")
            return

        for event in event_list:
            if event["id"] == event_id:
                if event["venue"] == venue:
                    print("\nYou have inserted an old venue.")
                    break

                else:
                    for event in event_list:
                        if event["id"] == event_id:
                            event_date = event["date"]
                            event_time = event["time"]
                            break

                    if is_valid_event_venue(event_date, event_time, venue, event_list):
                        for event in event_list:
                            if event["id"] == event_id:
                                event["venue"] = venue
                                update_file(17, files, lists)
                                print(f"\nThe venue of this event is updated to {venue}.")
                                return True


# Update event name
def update_event_name(event_list, event_id, files, lists):
    while True:
        name = input("\nEnter Event Name (type 'exit' to cancel): ").strip()

        if name.lower() == "exit":
            print("\nUpdate cancelled.")
            return

        for event in event_list:
            if event["id"] == event_id:
                if event["event_name"].strip('"') == name:
                    print("\nYou have inserted an old name.")
                    break

                else:
                    event["event_name"] = name
                    update_file(17, files, lists)
                    print(f"\nThe name of this event is updated to '{name}'.")
                    return True


# Edit event details
def edit_event_details(event_list, files, lists):
    print("\n===== Edit Event Details =====")
    print("\nAvailable Events:")

    for event in event_list:
        event_name = event["event_name"].strip('"')
        print(f"\nEvent ID: {event['id']} \nEvent Name: {event_name}")

    while True:
        event_id = input("\nEnter event ID (type 'exit' to cancel): ").strip().upper()

        if event_id.lower() == "exit":
            print("\nUpdate cancelled.")
            return

        if is_valid_id(event_id, event_list):
            while True:
                user_input = input(
                    "\n1: Update Date\n2: Update Time\n3: Update Venue\n4: Update Event Name\n5: Return to 'Event Management' Menu "
                    "\n\nPlease insert a number (1-5): ").strip()

                if user_input == "5":
                    return True

                if user_input == "1":
                    update_event_date(event_list, event_id, files, lists)

                elif user_input == "2":
                    update_event_time(event_list, event_id, files, lists)

                elif user_input == "3":
                    update_event_venue(event_list, event_id, files, lists)

                elif user_input == "4":
                    update_event_name(event_list, event_id, files, lists)

                else:
                    print("\nPlease insert a valid input (1-5).")


# Event management
def event_management(event_list, files, lists):
    while True:
        chosen4 = input(
            f"\n===== Event Management =====\n\n1: Add New Event \n2: Delete Event \n3: Edit Event Details \n4: Return to Main Menu \n\nEnter Number: ").strip()

        if chosen4 == "4":
            return True

        elif chosen4 == "1":  # add new event
            add_event(event_list, files)

        elif chosen4 == "2":  # delete event
            delete_event(event_list, files, lists)

        elif chosen4 == "3":  # edit event details
            edit_event_details(event_list, files, lists)

        else:
            print(f"\nPlease insert a valid input (1-4). ")


# Print staff profile
def view_staff_profile(current_user_id, staff_list):
    for staff in staff_list:
        if current_user_id == staff["id"]:
            print(f"\n===== User Profile =====")
            print(f"\nName: {staff['name']}")
            print(f"\nID: {staff['id']}")
            print(f"\nIC number: {staff['ic']}")
            print(f"\nPhone number: {staff['phone_no']}")
            username = staff["username"].strip('"')
            print(f"\nUsername: {username}")

            if staff["emergency_name"] == "&":
                print("\n*Emergency contact name needs to be updated.")

            else:
                print(f"\nEmergency Contact Name: {staff['emergency_name']}")

            if staff["emergency_no"] == "!":
                print("\n*Emergency contact number needs to be updated.")

            else:
                print(f"\nEmergency Contact Number: {staff['emergency_no']}")


from common_functions import edit_profile


# "Profile" menu
def staff_profile(current_user_id, role, staff_list, files, lists):
    while True:

        print("\n===== Profile =====")
        print("\na. View Profile")
        print("b. Edit Profile")
        print("c. Return to Main Menu")

        user_input = input("\nPlease choose an action to perform (a/b/c): ").strip().lower()

        if user_input == "a":
            view_staff_profile(current_user_id, staff_list)

        elif user_input == "b":
            edit_profile(current_user_id, role, files, lists)

        elif user_input == "c":
            return True

        else:
            print("\nPlease insert a valid option (a/b/c).")


# Student Assistance
def std_assistance(assistance_list, files, lists):
    question_found = False
    assistance_ids = []

    print("\n===== Student Assistance =====")
    print("\nAvailable Questions:")
    if not assistance_list:
        print("\nNo questions to be replied.")

    for assistance in assistance_list:
        if assistance["reply"] == "$":
            question_found = True
            question = assistance["question"].strip('"')
            print(f"\nQuestion ID: {assistance['id']}\nStudent ID: {assistance['student_id']}\nQuestion: {question}")
            assistance_ids.append(assistance["id"])

    if not question_found:
        print("\nAll questions have been replied.")

    else:
        while True:
            assistance_id = input("\nPlease enter a question ID to reply (type 'exit' to cancel): ").strip().upper()

            if assistance_id.lower() == "exit":
                print("\nExiting...")
                return

            if assistance_id in assistance_ids:
                while True:
                    reply = input("\nPlease insert your reply (type 'exit' to cancel): ").strip()

                    if reply.lower() == "exit":
                        print("\nExiting...")
                        return

                    if is_valid_quoted_input(reply):
                        reply = modify_input(reply)
                        break

                for assistance in assistance_list:
                    if assistance["id"] == assistance_id:
                        assistance["reply"] = reply
                        update_file(15, files, lists)
                        print("\nYour reply has been updated.")
                        return True

            else:
                print("\nPlease insert a valid question ID.")


# View student's financial information
def view_financial_info(student_list, financial_list):
    while True:
        student_id = input("\nEnter student ID (type 'exit' to cancel): ").strip().upper()

        if student_id.lower() == "exit":
            print("\nExiting...")
            return True

        if is_valid_id(student_id, student_list):
            break

    for financial in financial_list:
        if financial["student_id"] == student_id:
            print(
                f"\nFinance ID: {financial['id']}\nStudent ID: {financial['student_id']}\nTotal Paid: {financial['total_paid']}\nOutstanding: {financial['outstanding']}")
            return True


# Update student's financial information
def update_financial_info(student_list, financial_list, files, lists):
    while True:
        student_id = input("\nEnter student ID (type 'exit' to cancel): ").strip().upper()

        if student_id.lower() == "exit":
            print("\nUpdate cancelled.")
            return

        if is_valid_id(student_id, student_list):
            break

    for financial in financial_list:
        if financial["student_id"] == student_id:
            print(
                f"\nFinancial Information Found: \n\nStudent ID: {financial['student_id']}\nTotal paid: {financial['total_paid']}\nOutstanding: {financial['outstanding']}")
            while True:
                try:
                    input_money = input(f"\nEnter the new amount paid (type 'exit' to cancel): ").strip()

                    if input_money.lower() == "exit":
                        print("\nUpdate cancelled.")
                        return

                    input_money = float(input_money)

                    if input_money > float(financial["outstanding"]):
                        print("\nPlease insert a valid amount. This amount is greater than the outstanding amount.")
                        continue

                    result_total_paid = float(financial["total_paid"]) + input_money
                    total_paid = f"{result_total_paid:.2f}"

                    result_outstanding = float(financial["outstanding"]) - input_money
                    outstanding = f"{result_outstanding:.2f}"

                    financial["total_paid"] = total_paid
                    financial["outstanding"] = outstanding

                    update_file(16, files, lists)

                    print(f"\nThe total amount paid and outstanding balance are updated.")
                    return True

                except ValueError:
                    print("\nPlease insert a valid amount of money.")


# Financial
def financial(student_list, financial_list, files, lists):
    while True:
        user_input = input(
            "\n===== Financial =====\n\n1. View Student's Financial Information\n2. Update Student's Financial Information\n3. Return to Main Menu\n\nPlease choose an action to perform (1-3): ").strip()

        if user_input == "3":
            break

        elif user_input == "1":
            view_financial_info(student_list, financial_list)

        elif user_input == "2":
            update_financial_info(student_list, financial_list, files, lists)

        else:
            print("\nPlease insert a valid number (1-3).")


from common_functions import timetable_management
from common_functions import resource_allocation
from common_functions import announcements


def staff_main_menu(role, current_user_id, files, lists):
    while True:
        try:
            chosen = int(input(
                f"\n===== Staff's Main Menu =====\n\n1: User Profile\n2: Update Student Records \n3: Timetable Management \n4: Resource Allocation \n5: Event Management \n6: Student Assistance \n7: Financial \n8: Announcements \n9: Sign Out \n\nEnter number (1-9): "))

            if chosen == 1:
                staff_profile(current_user_id, role, lists[4], files, lists)

            elif chosen == 2:  # student records
                update_student_records(lists[2], lists[7], lists[16], files, lists)

            elif chosen == 3:  # timetable management
                timetable_management(lists[12], lists[5], lists[11], files, lists)

            elif chosen == 4:  # resource allocation
                resource_allocation(lists[12], files, lists, lists[3], lists[11])

            elif chosen == 5:  # event management
                event_management(lists[17], files, lists)

            elif chosen == 6:  # student assistance
                std_assistance(lists[15], files, lists)

            elif chosen == 7:  # financial
                financial(lists[2], lists[16], files, lists)

            elif chosen == 8:
                announcements(lists[14], files, lists)

            elif chosen == 9:
                return

            else:
                print(f"\nInvalid input. Please enter a number (1-9). ")

        except ValueError:
            print("\nInvalid input. Please enter a number (1-9).")