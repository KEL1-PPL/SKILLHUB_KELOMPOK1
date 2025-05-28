import React, { useState, useEffect } from 'react';
import { DatePicker, Table, Button, Modal, Input, message } from 'antd';
import { format } from 'date-fns';
import { MentorIncome } from '../../../models/MentorIncome';
import MentorIncomeService from '../../../services/MentorIncomeService';

interface IncomeReportProps {
  mentorId: string;
}

const IncomeReport: React.FC<IncomeReportProps> = ({ mentorId }) => {
  const [incomes, setIncomes] = useState<MentorIncome[]>([]);
  const [dateRange, setDateRange] = useState<[Date, Date]>([new Date(), new Date()]);
  const [isNoteModalVisible, setIsNoteModalVisible] = useState(false);
  const [selectedIncome, setSelectedIncome] = useState<MentorIncome | null>(null);
  const [note, setNote] = useState('');

  useEffect(() => {
    loadIncomes();
  }, [dateRange]);

  const loadIncomes = async () => {
    try {
      const data = await MentorIncomeService.getIncomeReport(
        mentorId,
        dateRange[0],
        dateRange[1]
      );
      setIncomes(data);
    } catch (error) {
      message.error('Gagal memuat data pendapatan');
    }
  };

  const handleAddNote = async () => {
    if (selectedIncome && note) {
      try {
        await MentorIncomeService.addNote(selectedIncome.id, note);
        message.success('Catatan berhasil ditambahkan');
        setIsNoteModalVisible(false);
        setNote('');
        loadIncomes();
      } catch (error) {
        message.error('Gagal menambahkan catatan');
      }
    }
  };

  const columns = [
    {
      title: 'Tanggal',
      dataIndex: 'transactionDate',
      render: (date: Date) => format(date, 'dd/MM/yyyy'),
    },
    {
      title: 'Jumlah',
      dataIndex: 'amount',
      render: (amount: number) => `Rp ${amount.toLocaleString()}`,
    },
    {
      title: 'Status',
      dataIndex: 'status',
    },
    {
      title: 'Catatan',
      dataIndex: 'note',
    },
    {
      title: 'Sumber',
      render: (record: MentorIncome) => 
        record.courseId ? 'Kursus' : 
        record.subscriptionId ? 'Langganan' : 
        'Lainnya',
    },
    {
      title: 'Aksi',
      render: (_: any, record: MentorIncome) => (
        <Button 
          onClick={() => {
            setSelectedIncome(record);
            setIsNoteModalVisible(true);
          }}
        >
          Tambah Catatan
        </Button>
      ),
    },
  ];

  return (
    <div className="income-report">
      <h2>Laporan Pendapatan</h2>
      <DatePicker.RangePicker 
        onChange={(dates) => {
          if (dates) {
            // Convert Moment dates to JavaScript Date objects
            const startDate = dates[0]?.toDate() ?? new Date();
            const endDate = dates[1]?.toDate() ?? new Date();
            setDateRange([startDate, endDate]);
          }
        }}
      />
      
      <Table 
        columns={columns}
        dataSource={incomes}
        rowKey="id"
        summary={(data) => {
          const total = data.reduce((sum, income) => 
            income.status === 'valid' ? sum + income.amount : sum, 0
          );
          return (
            <Table.Summary.Row>
              <Table.Summary.Cell index={0}>Total Pendapatan</Table.Summary.Cell>
              <Table.Summary.Cell index={1} colSpan={5}>
                Rp {total.toLocaleString()}
              </Table.Summary.Cell>
            </Table.Summary.Row>
          );
        }}
      />

      <Modal
        title="Tambah Catatan"
        open={isNoteModalVisible}
        onOk={handleAddNote}
        onCancel={() => setIsNoteModalVisible(false)}
      >
        <Input.TextArea
          value={note}
          onChange={(e) => setNote(e.target.value)}
          placeholder="Masukkan catatan..."
          rows={4}
        />
      </Modal>
    </div>
  );
};

export default IncomeReport;