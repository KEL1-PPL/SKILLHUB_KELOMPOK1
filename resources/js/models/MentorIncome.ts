export interface MentorIncome {
  id: string;
  mentorId: string;
  studentId: string;
  amount: number;
  transactionDate: Date;
  note?: string;
  status: 'valid' | 'corrected' | 'deleted';
  correctionNote?: string;
  createdAt: Date;
  updatedAt: Date;  // Pastikan ini bertipe Date, bukan new Date()
  courseId?: string;
  subscriptionId?: string;
}

export default MentorIncome;