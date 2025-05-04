public function up()
{
    Schema::table('users', function (Blueprint $table) {
        $table->enum('role', ['admin', 'mentor', 'student'])->default('student');
    });
}