# Writing/reading

def anonymise(timetable, names):
    for i in range(96):
        timetable[21 * i + 1] = "Timetable - " + names[i]
    return timetable


def main():
    with open("staffTT.csv") as file:
        timetable = file.readlines()
    with open("randomNamesList.txt") as file:
        names = file.readlines()
    timetable = anonymise(timetable, names)
    with open("staffTTanon.csv", "a+") as file:
        for line in timetable:
            file.write(line)


if __name__ == '__main__':
    main()
