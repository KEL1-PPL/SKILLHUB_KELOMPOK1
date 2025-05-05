import { MentorIncome } from '../models/MentorIncome';
import { dummyMentorIncomes } from '../data/dummy/mentorIncome';

class MentorIncomeService {
  private dummyData: MentorIncome[] = [...dummyMentorIncomes];

  async recordIncome(data: {
    mentorId: string;
    studentId: string;
    amount: number;
    courseId?: string;
    subscriptionId?: string;
  }): Promise<MentorIncome> {
    const newIncome: MentorIncome = {
      id: Math.random().toString(),
      ...data,
      transactionDate: new Date(),
      status: 'valid',
      createdAt: new Date(),
      updatedAt: new Date(),
    };
    this.dummyData.push(newIncome);
    return newIncome;
  }

  async getIncomeReport(
    mentorId: string,
    startDate: Date,
    endDate: Date
  ): Promise<MentorIncome[]> {
    return this.dummyData.filter(income => 
      income.mentorId === mentorId &&
      income.transactionDate >= startDate &&
      income.transactionDate <= endDate
    );
  }

  async addNote(incomeId: string, note: string): Promise<MentorIncome> {
    const income = this.dummyData.find(i => i.id === incomeId);
    if (!income) throw new Error('Income not found');
    
    income.note = note;
    income.updatedAt = new Date();
    return income;
  }

  async correctIncome(
    incomeId: string,
    correction: {
      status: 'corrected' | 'deleted';
      correctionNote: string;
    }
  ): Promise<MentorIncome> {
    const income = this.dummyData.find(i => i.id === incomeId);
    if (!income) throw new Error('Income not found');
    
    income.status = correction.status;
    income.correctionNote = correction.correctionNote;
    income.updatedAt = new Date();
    return income;
  }
}

export default new MentorIncomeService();