def score(lessons, teachers):  # for each lesson, give each teacher a score
    lessons_scores = {}
    for lesson in lessons:
        scores = {}
        for teacher in teachers:
            score = 0
            # calculate teacher score
            scores[teacher] = score
        lessons_scores[lesson] = scores
    return lessons_scores


def match(lessons_scores):  # match a teacher to each lesson based on scores
    matches = {}
    return matches


def main():
    lessons = []
    teachers = []
    lessons_scores = score(lessons, teachers)
    matches = match(lessons_scores)
    print(matches)


if __name__ == '__main__':
    main()
