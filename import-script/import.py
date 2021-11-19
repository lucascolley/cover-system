def add_commas(timetable):
    i = 0
    for line in timetable:
        timetable[i] = line[:-1] + ",,\n"
        i += 1
    return timetable


def name_parse(timetable):
    nameline = timetable[0]
    nameline = nameline.strip(",\n")
    nameline = nameline[12:]
    title = ""
    forename = ""
    surname = ""
    part = 0
    for letter in nameline:
        nameline = nameline[1:]
        if letter == " ":
            part += 1
        else:
            if part == 0:
                title += letter
            elif part == 1:
                forename += letter
            elif part == 2:
                surname += letter
    return title, forename, surname


def names_import(timetables):
    timetables = timetables[1:]
    processed_names = []
    for i in range(96):
        title, forename, surname = name_parse(timetables[:20])
        processed_names.append([title, forename, surname])
        timetables = timetables[21:]
    return processed_names


def timetable_parse(timetable):
    timetable = timetable[4:]
    processed_timetable = []
    i = 0
    while i <= 10:  # Data extracted for all 6 periods, which take 2 lines each
        lessonslines = [timetable[i].split(','), timetable[i + 1].split(',')]
        lessonsperiod = []
        for j in range(10):
            lessonsperiod.append(
                [lessonslines[0][j + 1], lessonslines[1][j + 1]])
        processed_timetable.append(lessonsperiod)
        i += 2
    return processed_timetable


def timetables_import(timetables):
    timetables = timetables[1:]
    processed_timetables = []
    for i in range(96):
        processed_timetables.append(timetable_parse(timetables[:20]))
        timetables = timetables[21:]
    return processed_timetables


def generate_staff_codes(names):
    staff_codes = []
    for name in names:
        staff_code = name[1][0] + name[2][0] + name[2][1].upper()
        staff_codes.append(staff_code)
    return staff_codes


def print_timetable(names, timetables, position):
    print("Title:", names[-1][0])
    print("Forename:", names[-1][1])
    print("Surname:", names[-1][2])
    print(timetables[-1])


def main():
    with open("staffTTanon.csv") as file:
        timetables = file.readlines()
    timetables = add_commas(timetables)
    names = names_import(timetables)
    timetables = timetables_import(timetables)
    print_timetable(names, timetables, -1)
    staff_codes = generate_staff_codes(names)
    print(staff_codes)


if __name__ == '__main__':
    main()
