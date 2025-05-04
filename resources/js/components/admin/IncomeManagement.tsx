import React, { useState } from 'react';
import { Table, Button, Modal, Input, message } from 'antd';
import MentorIncomeService from '../../services/MentorIncomeService';
// Error: Module tidak ditemukan
import { MentorIncome } from '../../models/MentorIncome';

const IncomeManagement: React.FC = () => {
  const [selectedIncome, setSelectedIncome] = useState<MentorIncome | null>(null);
  const [isCorrectionModalVisible, setIsCorrectionModalVisible] = useState(false);
  const [correctionNote, setCorrectionNote] = useState('');

  const handleCorrection = async (status: 'corrected' | 'deleted') => {
    if (selectedIncome && correctionNote) {
      try {
        await MentorIncomeService.correctIncome(selectedIncome.id, {
          status,
          correctionNote,
        });
        message.success('Data berhasil diperbarui');
        setIsCorrectionModalVisible(false);
        setCorrectionNote('');
        // Refresh data
      } catch (error) {
        message.error('Gagal memperbarui data');
      }
    }
  };

  const columns = [
    {
      title: 'Mentor',
      dataIndex: 'mentorId',
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
    // Perbaiki tipe data pada render function
    {
      title: 'Aksi',
      render: (_: any, record: MentorIncome) => (
        <Button 
          onClick={() => {
            setSelectedIncome(record);
            setIsCorrectionModalVisible(true);
          }}
        >
          Koreksi/Hapus
        </Button>
      ),
    },
  ];

  return (
    <div className="income-management">
      <h2>Manajemen Pendapatan Mentor</h2>
      
      <Table 
        columns={columns}
        dataSource={[]} // Implement with real data
        rowKey="id"
      />

      <Modal
        title="Koreksi Data Pendapatan"
        open={isCorrectionModalVisible} // Ganti visible menjadi open
        onCancel={() => setIsCorrectionModalVisible(false)}
        footer={[
          <Button key="correct" onClick={() => handleCorrection('corrected')}>
            Koreksi
          </Button>,
          <Button key="delete" danger onClick={() => handleCorrection('deleted')}>
            Hapus
          </Button>,
        ]}
      >
        <Input.TextArea
          value={correctionNote}
          onChange={(e) => setCorrectionNote(e.target.value)}
          placeholder="Masukkan alasan koreksi..."
          rows={4}
        />
      </Modal>
    </div>
  );
};

export default IncomeManagement;