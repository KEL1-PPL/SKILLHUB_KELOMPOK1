class Earning extends Model
{
    protected $fillable = [
        'mentor_id', 'amount', 'payment_date', 'correction_note',
    ];

    // Relasi dengan model Mentor
    public function mentor()
    {
        return $this->belongsTo(Mentor::class);
    }
}