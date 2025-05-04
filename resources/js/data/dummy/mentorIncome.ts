import { MentorIncome } from '../../models/MentorIncome';

export const dummyMentorIncomes: MentorIncome[] = [
  {
    id: '1',
    mentorId: 'mentor-1',
    studentId: 'student-1',
    amount: 150000,
    courseId: 'course-1',
    transactionDate: new Date('2024-03-01'),
    status: 'valid',
    createdAt: new Date(),
    updatedAt: new Date()
  },
  {
    id: '2',
    mentorId: 'mentor-1',
    studentId: 'student-2',
    amount: 100000,
    subscriptionId: 'sub-1',
    transactionDate: new Date('2024-03-05'),
    status: 'valid',
    note: 'Pembayaran langganan basic',
    createdAt: new Date(),
    updatedAt: new Date()
  },
  {
    id: '3',
    mentorId: 'mentor-1',
    studentId: 'student-3',
    amount: 200000,
    courseId: 'course-2',
    transactionDate: new Date('2024-03-10'),
    status: 'corrected',
    correctionNote: 'Pembayaran ganda',
    createdAt: new Date(),
    updatedAt: new Date()
  }
];