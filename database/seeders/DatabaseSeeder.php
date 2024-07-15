<?php

namespace Database\Seeders;

use App\Models\CategoryCelender;
use App\Models\Employee;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class DatabaseSeeder extends Seeder
{
    public $roles = ['Giám đốc', 'Quản lí sản xuất', 'KH-Mua hàng', 'Kho',
                     'Khuôn', 'Bảo trì điện', 'Kỹ thuật', 'QA-QC', 'Ngoại Quan',
                     'Sản xuất', 'Bảo trì + Kho', 'Quản lý', 'QC', 'Tổ trưởng sx'];
    public $employeesCode = ['2009000100', '2015000300', '2020122900', '2020020700', '2022030600', '2019060100',
                             '2018010900', '2020050400', '2017010500', '2022042200', '2019010300', '2019010400',
                             '2021120700', '2021120800', '2021050100', '2020040800', '2019010800', '2019020000',
                             '2022011800', '2022040500', '2022050900', '2022092700', '2022101500', '2022111800',
                             '2017010100', '2022022200', '2021111100', '2022050901', '2022060900', '2022030300',
                             '2022072300', '2022102400', '2022100200', '2022103100', '2023031500', '2023050500',
                             '2023013100', '2023030100', '2023062600', '2023041700', '2023041400', '2023061800',
                             '2023071800', '2023052900', '2023062400', '2023061400', '2023071000',

                             '20170006', '20170014', '20211012', '20170012', '20220210', '20220311',
                             '20160004', '20220712', '20220914',

                             '20230405', '20230815', '20230822', '20230829'];

    public $employeesName = ['Nguyễn Hồng Thọ', 'Nguyễn Được Thưởng', 'Nguyễn Thị Liến', 'Phan Hiệp Thành Danh',
                             'Nguyễn Trung Kiên', 'Phạm Minh Hải', 'Lê Đức Trọng', 'Trần Phi Long', 'Lê Thành Trung',
                             'Nguyễn Tấn Nghĩa', 'Phan Thành Triệu', 'Võ Duy Thanh', 'Phan Thị Ngọc Hân',
                             'Huỳnh Lê Nhật Ly', 'Nguyễn Thùy Trang', 'Nguyễn Thị Huế Trân', 'Nguyễn Thị Bé Năm',
                             'Nguyễn Thị Sang', 'Lê Thị Kim Chi', 'Nguyễn Thị Ngọc Linh', 'Nguyễn Thị Diễm Mi',
                             'Nguyễn Thị Ngọc Xinh', 'Trần Thị Nà Ni', 'Lê Thị Kim Thoa', 'Nguyễn Chí Tài',
                             'Võ Minh Hiếu', 'Danh Lực', 'Phạm Thị Kim Huệ', 'Đoàn Hữu Duy Linh',
                             'Nguyễn Hồng Duyên', 'Hồ Thị Ngọc Hiền', 'Lê Hoàng Thanh nam', 'Nguyễn Thành Nhân',
                             'Nguyễn Duy Linh', 'Phan Thị Thúy Hằng', 'Trần Thị Trúc', 'Huỳnh Thị Cẩm Tú',
                             'Đinh Phương Thảo', 'Nguyễn Khánh Duy', 'Trần Thị cẩm Tiên', 'Nguyễn Thị Khánh',
                             'Trương Tấn Đạt', 'Lê Minh Phát', 'Phan Châu Anh', 'Lý Thị Đa Rinh', 'Nguyễn Thị Hoa',
                             'Phan Thị Ngọc Trâm',

                             'Nguyễn Văn Nhường', 'Huỳnh Văn Thanh', 'Phạm Thị Quỳnh', 'Nguyễn Minh Thùy(5)',
                             'Trần Thanh Võ', 'Nguyễn Chí Nhân', 'Nguyễn Thị Tuyết', 'Trần Thị Ngọc Trâm',
                             'Tăng Thị Vành Na',

                             'Nguyễn Minh Khoa', 'Nguyễn Thị Nhớ', 'Hồ Sóng Vỗ', 'Nguyễn Thanh Bình'];

    public $employeesRole = [1, 2, 3, 4, 4, 5, 5, 5, 6, 6, 7, 8, 8, 8, 9, 9 , 9, 9, 9, 9 , 9, 9, 9, 9,
                             10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 9, 9, 9, 9, 10, 9, 10, 10, 10, 10,
                             9, 10, 9,
                             11, 12, 13, 14, 14, 14, 10, 10, 10,
                             10, 10, 10, 10];

    public $listCateCelender = [
        'Nhóm kỹ thuật - HN',
        'Nhóm QC Ca Ngày - HN',
        'Nhóm Đi Xoay Ca - HN',
        'Nhóm làm việc hành chính - HN',
        'Nhóm kỹ thuật - HC',
    ];

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->roleSeeder();
        $this->categoryCelender();
        $this->employeeSeeder();
    }

    public function roleSeeder()
    {

        for ($i = 0; $i < count($this->roles); $i++) {
            $role = new Role();
            $role->role_name = $this->roles[$i];
            $role->save();
        }
        $role = new Role();
        $role->role_name = 'admin';
        $role->save();
        $role = new Role();
        $role->role_name = 'manager';
        $role->save();
        $role = new Role();
        $role->role_name = 'accountant';
        $role->save();
    }

    public function categoryCelender()
    {
        try {
            for($i = 0; $i < count($this->listCateCelender); $i++) {
                $cateCelender = new CategoryCelender();
                $cateCelender->name = $this->listCateCelender[$i];
                $cateCelender->save();
            }
        } catch (\Exception $e) {
            Log::error('errors' . $e->getMessage() . ' getLine' . $e->getLine());
        }
        // $cateCelender->name
    }

    public function employeeSeeder() {

        try {
            for ($i = 0; $i < count($this->employeesCode); $i++) {
                $employee = new Employee();
                $employee->name = $this->employeesName[$i];
                $employee->email = rand(111111111,999999999) . "@gmail.com";
                $employee->phone = "0" . rand(111111111,999999999);
                $employee->code = $this->employeesCode[$i];
                $employee->address = "Sài Gòn";
                $employee->gender = "Nam";
                $employee->home_town = "Long An";
                $employee->birthday = "2002/09/24";
                $employee->CCCD = rand(11111111111,99999999999);
                $employee->photo = "a.jpg";
                $employee->card_photo = "a.jpg";
                $employee->marital_status = "độc thân";
                $employee->date_joining = "2002/09/24";
                $employee->role_id = $this->employeesRole[$i];
                $employee->password = bcrypt($this->employeesCode[$i]);
                $employee->category_celender_id = rand(1,5);
                $employee->save();
            }
            $employee = new Employee();
            $employee->name = "Admin(Nguyễn Hồng Thọ)";
            $employee->phone = "ctyvinhvinh";
            $employee->role_id = 15;
            $employee->photo = 'avata.jpg';
            $employee->password = bcrypt("123456");
            $employee->category_celender_id = rand(1,5);
            $employee->save();

            $employee = new Employee();
            $employee->name = "Admin(Nguyễn Được Thưởng)";
            $employee->phone = "ctyvinhvinhphat1";
            $employee->role_id = 15;
            $employee->photo = 'avata.jpg';
            $employee->password = bcrypt("123456");
            $employee->category_celender_id = rand(1,5);
            $employee->save();

            $employee = new Employee();
            $employee->name = "Admin(Nguyễn Hữu Lộc)";
            $employee->phone = "ctyvinhvinhphat2";
            $employee->role_id = 15;
            $employee->photo = 'avata.jpg';
            $employee->password = bcrypt("123456");
            $employee->category_celender_id = rand(1,5);
            $employee->save();

            $employee = new Employee();
            $employee->name = "Accountant(Nguyễn Thị Liến)";
            $employee->phone = "ctyvinhvinhphat3";
            $employee->role_id = 17;
            $employee->photo = 'avata.jpg';
            $employee->password = bcrypt("123456");
            $employee->category_celender_id = rand(1,5);
            $employee->save();
        } catch (\Exception $e) {
            Log::error('errors' . $e->getMessage() . ' getLine' . $e->getLine());
        }
    }
}
